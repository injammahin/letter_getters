<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Match Approved</title>
</head>

<body style="margin:0;padding:0;background:#f7f4fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <div
        style="max-width:640px;margin:40px auto;background:#ffffff;border:1px solid rgba(17,17,17,0.08);border-radius:24px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#CB148B,#620A88);padding:24px 28px;color:#ffffff;">
            <h1 style="margin:0;font-size:24px;font-weight:700;">Your pen pal match is approved</h1>
            <p style="margin:8px 0 0;opacity:0.9;">A new approved pen pal is now visible in My Pen Pals.</p>
        </div>

        <div style="padding:28px;">
            <p style="margin:0 0 16px;font-size:15px;line-height:1.8;">
                Hello {{ $child->name }},
            </p>

            <p style="margin:0 0 18px;font-size:15px;line-height:1.8;color:#4b5563;">
                Your match with <strong>{{ $matchedChild->name }}</strong> has been approved.
                You can now see this pen pal in your child dashboard and use the approved pen pal actions.
            </p>

            <div style="background:#faf7fc;border:1px solid rgba(17,17,17,0.06);border-radius:18px;padding:18px;">
                <p style="margin:0 0 10px;font-size:14px;"><strong>Your Name:</strong> {{ $child->name }}</p>
                <p style="margin:0;font-size:14px;"><strong>Approved Pen Pal:</strong> {{ $matchedChild->name }}</p>
            </div>
        </div>
    </div>
</body>

</html>