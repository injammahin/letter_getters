<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Ticket Reply</title>
</head>
<body style="margin:0; padding:0; background:#f7f4fb; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <div style="max-width:640px; margin:40px auto; background:#ffffff; border:1px solid rgba(17,17,17,0.08); border-radius:24px; overflow:hidden;">
        <div style="background:linear-gradient(135deg,#CB148B,#620A88); padding:24px 28px; color:#ffffff;">
            <h1 style="margin:0; font-size:24px; font-weight:700;">Support Reply</h1>
            <p style="margin:8px 0 0; opacity:0.9;">Your support ticket has a new reply.</p>
        </div>

        <div style="padding:28px;">
            <p style="margin:0 0 16px; font-size:15px; line-height:1.8;">Hello {{ $ticket->full_name }},</p>

            <p style="margin:0 0 18px; font-size:15px; line-height:1.8; color:#4b5563;">
                Our support team has replied to your ticket.
            </p>

            <div style="background:#faf7fc; border:1px solid rgba(17,17,17,0.06); border-radius:18px; padding:18px; margin-bottom:18px;">
                <p style="margin:0 0 10px; font-size:14px;"><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p style="margin:0; font-size:14px;"><strong>Replied By:</strong> {{ $responderName }}</p>
            </div>

            <div style="background:#ffffff; border:1px solid rgba(17,17,17,0.08); border-radius:18px; padding:18px;">
                <p style="margin:0 0 8px; font-size:14px; font-weight:700;">Reply Message</p>
                <p style="margin:0; font-size:14px; line-height:1.9; white-space:pre-line;">{{ $reply->message }}</p>
            </div>
        </div>
    </div>
</body>
</html>