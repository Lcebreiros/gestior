@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@elseif (trim($slot) === config('app.name'))
<img src="{{ asset('images/gestior.png') }}" class="logo" alt="Gestior Logo" style="width: 80px; height: 80px; filter: drop-shadow(0 8px 24px rgba(124, 58, 237, 0.5));">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
