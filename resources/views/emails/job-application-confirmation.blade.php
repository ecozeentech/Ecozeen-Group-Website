@component('mail::message')
# Thank You For Applying

Hi {{ $application->full_name }},

We have received your application for **{{ $application->jobListing->title }}** at Ecozeen Group. Our hiring team will review your submission and reach out if there is a potential fit.

Thanks,<br>
Ecozeen Group Talent Team
@endcomponent
