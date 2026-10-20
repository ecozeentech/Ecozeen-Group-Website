@component('mail::message')
# New Contact Inquiry

A new **{{ \Illuminate\Support\Str::headline($inquiry->inquiry_type) }}** inquiry was submitted through the Ecozeen Group website.

- **Name:** {{ $inquiry->full_name }}
- **Email:** {{ $inquiry->email }}
@if ($inquiry->phone)
- **Phone:** {{ $inquiry->phone }}
@endif
@if ($inquiry->organization)
- **Organization:** {{ $inquiry->organization }}
@endif
@if ($inquiry->jurisdiction)
- **Jurisdiction:** {{ $inquiry->jurisdiction }}
@endif

**Message:**

{{ $inquiry->message }}

@component('mail::button', ['url' => url('/admin/inquiries/'.$inquiry->id)])
View in Admin Dashboard
@endcomponent

Thanks,<br>
Ecozeen Group Website
@endcomponent
