<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden; }
        .header { background-color: #000; color: #fff; padding: 25px; text-align: center; }
        .content { padding: 30px; line-height: 1.6; }
        .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Application Update</h2>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>Thank you for your interest in joining <strong>Al-Amin Tuition Centre</strong> as a tutor.</p>
            <p>We have carefully reviewed your application and qualifications. At this time, we regret to inform you that we will not be moving forward with your application.</p>
            <p>We appreciate the time you took to apply and wish you the very best in your future professional endeavors.</p>
            <br>
            <p>Best Regards,<br><strong>Management Team</strong><br>Al-Amin Tuition Centre</p>
        </div>
        <div class="footer">
            This is an automated message. Please do not reply to this email.
        </div>
    </div>
</body>
</html>
