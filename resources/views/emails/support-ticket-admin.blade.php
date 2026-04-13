<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Support Ticket</title>
</head>
<body style="margin:0; padding:0; background:#f7f4fb; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <div style="max-width:640px; margin:40px auto; background:#ffffff; border:1px solid rgba(17,17,17,0.08); border-radius:24px; overflow:hidden;">
        <div style="background:linear-gradient(135deg,#620A88,#CB148B); padding:24px 28px; color:#ffffff;">
            <h1 style="margin:0; font-size:24px; font-weight:700;">New Support Ticket</h1>
            <p style="margin:8px 0 0; opacity:0.9;">A new ticket has been submitted from the support page.</p>
        </div>

        <div style="padding:28px;">
            <div style="background:#faf7fc; border:1px solid rgba(17,17,17,0.06); border-radius:18px; padding:18px;">
                <p style="margin:0 0 10px; font-size:14px;"><strong>Ticket Number:</strong> {{ $ticket->ticket_number }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Name:</strong> {{ $ticket->full_name }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Email:</strong> {{ $ticket->email }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Subject:</strong> {{ $ticket->subject }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Category:</strong> {{ ucfirst($ticket->category) }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                <p style="margin:0 0 10px; font-size:14px;"><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
                <p style="margin:0; font-size:14px; line-height:1.8; white-space:pre-line;"><strong>Message:</strong> {{ $ticket->message }}</p>
            </div>
        </div>
    </div>
</body>
</html>