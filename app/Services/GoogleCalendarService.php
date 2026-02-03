<?php

namespace App\Services;

use App\Models\User;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleEvent;
use Google\Service\Oauth2;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected GoogleClient $client;
    protected ?User $user = null;
    protected ?GoogleCalendar $service = null;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('google-calendar.client_id'));
        $this->client->setClientSecret(config('google-calendar.client_secret'));
        $this->client->setRedirectUri(config('google-calendar.redirect_uri'));
        $this->client->setScopes(config('google-calendar.scopes'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    /**
     * Get authorization URL
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Attach user context
     */
    public function forUser(User $user): self
    {
        $this->user = $user;

        if ($user->google_access_token) {
            $this->client->setAccessToken($user->google_access_token);

            // Auto-refresh expired tokens
            if ($this->client->isAccessTokenExpired() && $user->google_refresh_token) {
                try {
                    $this->client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                    $newToken = $this->client->getAccessToken();

                    $user->update([
                        'google_access_token' => $newToken,
                        'google_token_expires_at' => now()->addSeconds($newToken['expires_in'] ?? 3600),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error refreshing Google token', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return $this;
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code): array
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new \Exception('Error getting access token: ' . $token['error']);
        }

        // Get user email from token
        $this->client->setAccessToken($token);
        $oauth2 = new Oauth2($this->client);
        $userInfo = $oauth2->userinfo->get();

        return [
            'access_token' => $token,
            'refresh_token' => $token['refresh_token'] ?? null,
            'expires_at' => now()->addSeconds($token['expires_in'] ?? 3600),
            'email' => $userInfo->email,
        ];
    }

    /**
     * Check if user is connected
     */
    public function isConnected(): bool
    {
        if (!$this->user || !$this->user->google_access_token) {
            return false;
        }

        return !$this->client->isAccessTokenExpired();
    }

    /**
     * Get Calendar service instance
     */
    protected function getService(): GoogleCalendar
    {
        if (!$this->service) {
            $this->service = new GoogleCalendar($this->client);
        }

        return $this->service;
    }

    /**
     * Create calendar event
     */
    public function createEvent(
        string $summary,
        \DateTime $start,
        \DateTime $end,
        ?string $description = null,
        ?string $location = null,
        string $eventType = 'custom'
    ): ?GoogleEvent {
        try {
            $service = $this->getService();

            $event = new GoogleEvent([
                'summary' => $summary,
                'description' => $description,
                'location' => $location,
                'start' => [
                    'dateTime' => $start->format(\DateTime::RFC3339),
                    'timeZone' => $this->user->timezone ?? config('app.timezone', 'UTC'),
                ],
                'end' => [
                    'dateTime' => $end->format(\DateTime::RFC3339),
                    'timeZone' => $this->user->timezone ?? config('app.timezone', 'UTC'),
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'popup', 'minutes' => config('google-calendar.event_settings.default_reminder_minutes')],
                        ['method' => 'email', 'minutes' => config('google-calendar.event_settings.default_reminder_minutes')],
                    ],
                ],
            ]);

            // Set color based on event type
            $colorId = config("google-calendar.event_settings.colors.{$eventType}");
            if ($colorId) {
                $event->setColorId($colorId);
            }

            $calendarId = $this->user->google_calendar_id ?? 'primary';
            $createdEvent = $service->events->insert($calendarId, $event);

            Log::info('Google Calendar event created', [
                'user_id' => $this->user->id,
                'event_id' => $createdEvent->getId(),
                'summary' => $summary,
            ]);

            return $createdEvent;
        } catch (\Exception $e) {
            Log::error('Error creating Google Calendar event', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Update calendar event
     */
    public function updateEvent(
        string $eventId,
        string $summary,
        \DateTime $start,
        \DateTime $end,
        ?string $description = null,
        ?string $location = null
    ): ?GoogleEvent {
        try {
            $service = $this->getService();
            $calendarId = $this->user->google_calendar_id ?? 'primary';

            // Get existing event
            $event = $service->events->get($calendarId, $eventId);

            // Update fields
            $event->setSummary($summary);
            $event->setDescription($description);
            $event->setLocation($location);
            $event->setStart(new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $start->format(\DateTime::RFC3339),
                'timeZone' => $this->user->timezone ?? config('app.timezone', 'UTC'),
            ]));
            $event->setEnd(new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $end->format(\DateTime::RFC3339),
                'timeZone' => $this->user->timezone ?? config('app.timezone', 'UTC'),
            ]));

            $updatedEvent = $service->events->update($calendarId, $eventId, $event);

            Log::info('Google Calendar event updated', [
                'user_id' => $this->user->id,
                'event_id' => $eventId,
            ]);

            return $updatedEvent;
        } catch (\Exception $e) {
            Log::error('Error updating Google Calendar event', [
                'user_id' => $this->user->id,
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Delete calendar event
     */
    public function deleteEvent(string $eventId): bool
    {
        try {
            $service = $this->getService();
            $calendarId = $this->user->google_calendar_id ?? 'primary';

            $service->events->delete($calendarId, $eventId);

            Log::info('Google Calendar event deleted', [
                'user_id' => $this->user->id,
                'event_id' => $eventId,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting Google Calendar event', [
                'user_id' => $this->user->id,
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Disconnect user from Google Calendar
     */
    public function disconnect(): void
    {
        if ($this->user) {
            $this->user->update([
                'google_access_token' => null,
                'google_refresh_token' => null,
                'google_token_expires_at' => null,
                'google_calendar_id' => null,
                'google_email' => null,
                'google_calendar_sync_enabled' => false,
            ]);
        }
    }
}
