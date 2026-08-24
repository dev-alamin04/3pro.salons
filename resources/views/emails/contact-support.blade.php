<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        body      { margin:0; padding:0; background:#f4f6fb; font-family:Arial,Helvetica,sans-serif; color:#1f2937; }
        .container{ width:100%; max-width:640px; margin:0 auto; padding:24px; }
        .card     { background:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 20px 40px rgba(15,23,42,.08); }
        .hero     { background:#1f2937; color:#ffffff; padding:40px 24px; text-align:center; }
        .hero img { display:block; margin:0 auto 20px; }
        .hero h1  { margin:0; font-size:26px; letter-spacing:-.5px; }
        .hero p   { margin:10px 0 0; font-size:15px; opacity:.85; }
        .content  { padding:32px 28px; font-size:16px; line-height:1.6; color:#374151; }
        .content p{ margin:0 0 16px; }
        .content p:last-child{ margin-bottom:0; }
        .highlight{ background:#eef2ff; border-left:4px solid #4f46e5; border-radius:0 8px 8px 0; padding:16px 20px; margin:24px 0; }
        .highlight p{ margin:0; font-size:15px; color:#3730a3; font-weight:600; }
        .divider  { height:1px; background:#e5e7eb; margin:24px 0; }
        .footer   { padding:20px 28px 28px; font-size:13px; color:#9ca3af; text-align:center; }
        .footer a { color:#4f46e5; text-decoration:none; }
        .team-sig { margin-top:24px; font-weight:700; color:#1f2937; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">

            {{-- Hero --}}
            <div class="hero">
                <img src="{{ $logoUrl }}" alt="{{ $appName }} logo" width="90" />
                <h1>We got your message 📬</h1>
                <p>Our support team is on it.</p>
            </div>

            {{-- Body --}}
            <div class="content">
                <p>Hey {{ $firstName }},</p>

                <p>
                    Thanks for reaching out — your support request has been received and
                    our team will take a look shortly.
                </p>

                <div class="highlight">
                    <p>⏱ We typically respond within 1–2 business days.</p>
                </div>

                <p>
                    If this is urgent or you need to add more info, just reply to this email —
                    it goes straight to us.
                </p>

                <div class="divider"></div>

                <p class="team-sig">Talk soon,<br>The {{ $appName }} Team</p>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>
                    You're receiving this because you submitted a support request.<br>
                    <a href="{{ url('/') }}">{{ $appName }}</a>
                </p>
            </div>

        </div>
    </div>
</body>
</html>
