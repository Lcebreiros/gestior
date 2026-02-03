<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Obtener todos los eventos del calendario para un mes específico
     */
    public function getMonth(Request $request, $year, $month)
    {
        $user = $request->user();

        $events = CalendarEvent::where('user_id', $user->id)
            ->inMonth($year, $month)
            ->with('related')
            ->orderBy('event_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'meta' => [
                'year' => $year,
                'month' => $month,
                'total' => $events->count(),
            ],
        ]);
    }

    /**
     * Obtener eventos en un rango de fechas
     */
    public function getRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $user = $request->user();

        $events = CalendarEvent::where('user_id', $user->id)
            ->inRange($request->start_date, $request->end_date)
            ->with('related')
            ->orderBy('event_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'meta' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total' => $events->count(),
            ],
        ]);
    }

    /**
     * Obtener próximos eventos
     */
    public function getUpcoming(Request $request)
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);

        $events = CalendarEvent::where('user_id', $user->id)
            ->where('event_date', '>=', now())
            ->pending()
            ->with('related')
            ->orderBy('event_date')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'meta' => [
                'total' => $events->count(),
                'limit' => $limit,
            ],
        ]);
    }

    /**
     * Obtener eventos vencidos
     */
    public function getOverdue(Request $request)
    {
        $user = $request->user();

        $events = CalendarEvent::where('user_id', $user->id)
            ->overdue()
            ->with('related')
            ->orderBy('due_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'meta' => [
                'total' => $events->count(),
            ],
        ]);
    }

    /**
     * Obtener eventos por tipo
     */
    public function getByType(Request $request, $type)
    {
        $user = $request->user();

        $events = CalendarEvent::where('user_id', $user->id)
            ->ofType($type)
            ->with('related')
            ->orderBy('event_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'meta' => [
                'type' => $type,
                'total' => $events->count(),
            ],
        ]);
    }

    /**
     * Obtener resumen del calendario
     */
    public function getSummary(Request $request)
    {
        $user = $request->user();

        $today = Carbon::today();
        $thisWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $thisMonth = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];

        $summary = [
            'today' => CalendarEvent::where('user_id', $user->id)
                ->whereDate('event_date', $today)
                ->count(),

            'this_week' => CalendarEvent::where('user_id', $user->id)
                ->whereBetween('event_date', $thisWeek)
                ->count(),

            'this_month' => CalendarEvent::where('user_id', $user->id)
                ->whereBetween('event_date', $thisMonth)
                ->count(),

            'overdue' => CalendarEvent::where('user_id', $user->id)
                ->overdue()
                ->count(),

            'pending' => CalendarEvent::where('user_id', $user->id)
                ->pending()
                ->count(),

            'completed_this_month' => CalendarEvent::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('completed_at', $thisMonth)
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Marcar evento como completado
     */
    public function markAsCompleted(Request $request, $id)
    {
        $user = $request->user();

        $event = CalendarEvent::where('user_id', $user->id)
            ->findOrFail($id);

        $event->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Evento marcado como completado',
            'data' => $event->fresh(),
        ]);
    }

    /**
     * Crear evento personalizado
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|in:order_delivery,payment_deadline,tax_due,supply_reorder,expense_payment,reminder,custom',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|size:7',
            'event_date' => 'required|date',
            'due_date' => 'nullable|date',
            'reminder_date' => 'nullable|date',
        ]);

        $user = $request->user();

        $event = CalendarEvent::create([
            ...$validated,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evento creado exitosamente',
            'data' => $event,
        ], 201);
    }

    /**
     * Actualizar evento
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|size:7',
            'event_date' => 'sometimes|date',
            'due_date' => 'nullable|date',
            'reminder_date' => 'nullable|date',
            'status' => 'sometimes|in:pending,completed,overdue,cancelled',
        ]);

        $user = $request->user();

        $event = CalendarEvent::where('user_id', $user->id)
            ->findOrFail($id);

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado exitosamente',
            'data' => $event->fresh(),
        ]);
    }

    /**
     * Eliminar evento
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $event = CalendarEvent::where('user_id', $user->id)
            ->findOrFail($id);

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento eliminado exitosamente',
        ]);
    }
}
