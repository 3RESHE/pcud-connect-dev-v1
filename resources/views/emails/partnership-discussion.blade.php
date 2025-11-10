@component('mail::message')
Dear {{ $partnership->contact_name }},

{{ $message }}

**Partnership Proposal Details:**
- **Title:** {{ $partnership->activity_title }}
- **Organization:** {{ $partnership->organization_name }}
- **Activity Type:** {{ $partnership->getActivityTypeDisplay() }}

Please log in to your partner account to respond or provide additional information.

Best regards,<br>
{{ $adminName }}<br>
PCU-DASMA Connect Team

@component('mail::button', ['url' => route('partner.dashboard')])
View Partnership Proposal
@endcomponent
@endcomponent
