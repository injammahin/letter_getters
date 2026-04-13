<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Ticket Created</title>
</head>
<body style="margin:0; padding:0; background:#f7f4fb; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <div style="max-width:640px; margin:40px auto; background:#ffffff; border:1px solid rgba(17,17,17,0.08); border-radius:24px; overflow:hidden;">
        <div style="background:linear-gradient(135deg,#CB148B,#620A88); padding:24px 28px; color:#ffffff;">
            <h1 style="margin:0; font-size:24px; font-weight:700;">Support Ticket Created</h1>
            <p style="margin:8px 0 0; opacity:0.9;">Your request has been received successfully.</p>
        </div>

        <div style="padding:28px;">
            <p style="margin:0 0 16px; font-size:15px; line-height:1.8;">
                Hello {{ $ticket->full_name }},
            </p>

            <p style="margin:0 0 20px; font-size:15px; line-height:1.8; color:#4b5563;">
                Your support ticket was created successfully. Our team will review it and get back to you as soon as possible.
            </p>

            <div style="background:#faf7fc; border:1px solid rgba(17,17,17,0.06); border-radius:18px; padding:18px;">
                <p style="margin:0 0 10px; font-size:14px;"><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Category:</strong> {{ ucfirst($ticket->category) }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                <p style="margin:0; font-size:14px; line-height:1.8;">
                    <strong>Brief:</strong>
                    {{ \Illuminate\Support\Str::limit($ticket->message, 180) }}
                </p>
            </div>

            <p style="margin:22px 0 0; font-size:14px; line-height:1.8; color:#6b7280;">
                Please keep your ticket number for future reference.
            </p>
        </div>
    </div>
</body>
</html>