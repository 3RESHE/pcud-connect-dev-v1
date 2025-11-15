@component('mail::message')
# Event Update - {{ $event->title }}

Hello {{ $recipientName }},

{{ $message }}

@component('mail::panel')
**Event Details:**
- **Event:** {{ $event->title }}
- **Date:**
  @if($event->is_multiday && $event->end_date)
    {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('F d, Y') }}
  @else
    {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
  @endif
- **Time:** {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
- **Format:** {{ ucfirst(str_replace('_', ' ', $event->event_format)) }}
@if($event->event_format === 'inperson' || $event->event_format === 'hybrid')
  - **Venue:** {{ $event->venue_name }}
@endif
@endcomponent

@component('mail::button', ['url' => route('staff.events.show', $event->id)])
View Event Details
@endcomponent

Thank you,
{{ config('app.name') }}
@endcomponent
