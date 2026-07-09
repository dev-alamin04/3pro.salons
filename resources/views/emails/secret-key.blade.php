<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your 3 Pro Key</title>
</head>
<body style="margin:0;padding:0;background-color:#0d0f10;font-family:'Segoe UI',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d0f10;padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#1a1d1f;border-radius:16px;overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#111314;padding:32px;text-align:center;border-bottom:1px solid #2a2d2f;">
                            <img src="{{ asset($systemSetting->logo) }}" alt="logo" style="height:48px;margin-bottom:16px;">
                            <h1 style="color:#ffffff;font-size:22px;font-weight:600;margin:0;">{{ $systemSetting->system_name }}</h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px 48px;">
                            <p style="color:#8a9294;font-size:14px;margin:0 0 8px;">Hello {{$user->name}},</p>
                            <h2 style="color:#ffffff;font-size:20px;font-weight:600;margin:0 0 24px;">
                                You've been assigned to a salon!
                            </h2>
                            <p style="color:#8a9294;font-size:14px;line-height:1.7;margin:0 0 32px;">
                                Your account has been set up. Use the 3 Pro key below to access your dashboard.
                                Keep this key safe and do not share it with anyone.
                            </p>

                            {{-- Secret Key Box --}}
                            <div style="background-color:#111314;border:1px solid #2a2d2f;border-radius:12px;padding:24px;text-align:center;margin-bottom:32px;">
                                <p style="color:#8a9294;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin:0 0 12px;">Your 3 Pro Key</p>
                                <p style="color:#00D4FF;font-size:28px;font-weight:700;letter-spacing:4px;margin:0;font-family:monospace;">
                                    {{ $user->secret_key }}
                                </p>
                            </div>

                            {{-- Set Password Button --}}
                            <div style="text-align:center;margin-bottom:32px;">
                                <a href="{{ config('app.frontend_url') }}?key={{ urlencode($user->secret_key) }}"
                                style="display:inline-block;background-color:#00D4FF;color:#04151a;font-size:15px;font-weight:600;padding:14px 40px;border-radius:8px;text-decoration:none;letter-spacing:0.5px;">
                                    Set Password →
                                </a>
                            </div>

                            <p style="color:#4a5254;font-size:12px;text-align:center;margin:0;">
                                If you did not expect this email, please ignore it.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#111314;padding:24px;text-align:center;border-top:1px solid #2a2d2f;">
                            <p style="color:#4a5254;font-size:12px;margin:0;">© {{ date('Y') }} Dayna. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
