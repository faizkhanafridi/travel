<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Set your password</title></head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2>Welcome, {{ $user->name }}!</h2>
    <p>An account has been created for you on {{ config('app.name') }}.</p>
    <p>Please click the button below to set your password. This link expires in 48 hours.</p>
    <p style="margin: 24px 0;">
        <a href="{{ $url }}"
           style="background:#2563eb;color:#fff;padding:12px 20px;border-radius:6px;text-decoration:none;">
            Set Password
        </a>
    </p>
    <p>Or copy this URL: <br><small>{{ $url }}</small></p>
    <p>Regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>