{{--
    Standalone error layout: intentionally does NOT query the database
    (WebsiteSetting, ThemeSetting, NavigationService) so that it still
    renders correctly even if the error is caused by a database outage.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Ecozeen Group</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @endif
</head>
<body style="font-family: Inter, system-ui, sans-serif; background:#F8FAFC; color:#111827; margin:0;">
    <div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem;">
        <div style="max-width:32rem; text-align:center;">
            <p style="font-weight:800; font-size:3rem; color:#0F172A; margin:0;">{{ $code }}</p>
            <h1 style="font-size:1.5rem; font-weight:700; color:#111827; margin:0.5rem 0;">{{ $title }}</h1>
            <p style="color:#64748B; margin-bottom:1.5rem;">{{ $message }}</p>
            <a href="{{ url('/') }}" style="display:inline-block; background:#0F172A; color:#fff; padding:0.65rem 1.5rem; border-radius:0.5rem; text-decoration:none; font-weight:600;">
                Return to Homepage
            </a>
        </div>
    </div>
</body>
</html>
