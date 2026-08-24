<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        body       { margin:0; padding:0; background:#f4f6fb; font-family:Arial,Helvetica,sans-serif; color:#1f2937; }
        .wrap      { width:100%; max-width:640px; margin:0 auto; padding:24px; }
        .card      { background:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 16px 40px rgba(0,0,0,.08); }
        .hero      { padding:36px 28px 28px; text-align:center;
                     background: linear-gradient(135deg, #00b4c8 0%, #0093a8 100%); }
        .hero img  { display:block; margin:0 auto 16px; border-radius:8px; }
        .hero h1   { margin:0; font-size:24px; color:#ffffff; letter-spacing:-.4px; }
        .hero p    { margin:8px 0 0; font-size:14px; color:rgba(255,255,255,.85); }
        .body      { padding:32px 28px; font-size:15px; line-height:1.7; color:#374151; }
        .body p    { margin:0 0 16px; }
        .reply-box { background:#f0fbfd; border-left:4px solid #00b4c8;
                     border-radius:0 12px 12px 0; padding:20px 22px; margin:20px 0; }
        .reply-box p { margin:0; white-space:pre-wrap; color:#1f2937; font-size:15px; line-height:1.8; }
        .sig       { margin-top:24px; padding-top:16px; border-top:1px solid #e5e7eb; }
        .sig p     { margin:0 0 4px; }
        .sig .team { font-weight:700; color:#00b4c8; font-size:15px; }
        .footer    { padding:16px 28px 24px; text-align:center; font-size:12px; color:#9ca3af; background:#f9fafb; }
        .footer a  { color:#00b4c8; text-decoration:none; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">

            {{-- Hero --}}
            <div class="hero">
                <img src="{{ $logoUrl }}" alt="{{ $appName }}" width="80" />
                <h1>Reply from {{ $appName }}</h1>
                <p>Our support team has responded to your request.</p>
            </div>

            {{-- Body --}}
            <div class="body">
                <p>Hey {{ $firstName }},</p>
                <p>Our support team has reviewed your request and sent you the following reply:</p>

                <div class="reply-box">
                    <p>{{ $replyMessage }}</p>
                </div>

                <p>
                    If you have any follow-up questions or need further assistance,
                    just reply to this email — it goes straight to our team.
                </p>

                <div class="sig">
                    <p class="team">The {{ $appName }} Team</p>
                    <p style="color:#6b7280;font-size:13px;">We typically respond within 1–2 business days.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <p>You're receiving this because you submitted a support request.<br>
                   <a href="{{ url('/') }}">{{ $appName }}</a></p>
            </div>

        </div>
    </div>
</body>
</html>
