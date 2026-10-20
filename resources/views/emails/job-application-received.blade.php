@component('mail::message')
# New Job Application

**{{ $application->full_name }}** applied for **{{ $application->jobListing->title }}**.

- **Email:** {{ $application->email }}
@if ($application->phone)
- **Phone:** {{ $application->phone }}
@endif

@component('mail::button', ['url' => url('/admin/job-applications/'.$application->id)])
Review Application
@endcomponent

Thanks,<br>
Ecozeen Group Website
@endcomponent
