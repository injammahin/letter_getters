<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Child Registration</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8f5fb; padding: 30px;">
    <div style="max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 18px; padding: 32px; border: 1px solid rgba(17,17,17,0.08);">
        <h2 style="margin: 0 0 14px; color: #111111;">Letter Getters</h2>

        <p style="font-size: 15px; color: #333333; line-height: 1.8;">
            A child has started registration on Letter Getters and listed this email as the parent/guardian contact.
        </p>

        <p style="font-size: 15px; color: #333333; line-height: 1.8;">
            Please review and approve the child registration from the link below:
        </p>

        <p style="margin: 28px 0;">
            <a href="{{ route('parent.approval.show', $approval->token) }}"
               style="display: inline-block; background: linear-gradient(135deg, #CB148B, #620A88); color: #ffffff; text-decoration: none; padding: 14px 22px; border-radius: 999px; font-weight: bold;">
                Review Child Registration
            </a>
        </p>

        <p style="font-size: 13px; color: #666666; line-height: 1.8;">
            This link expires on {{ optional($approval->expires_at)->format('d M Y h:i A') }}.
        </p>
    </div>
</body>
</html>