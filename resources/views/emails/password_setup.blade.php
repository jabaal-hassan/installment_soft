<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Set Up Your Password</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Hello {{ $user->name }},</h2>

        <p>You have been invited to set up your password. Please click the button below to continue:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $setupUrl }}"
                style="background-color: #4CAF50;
                      color: white;
                      padding: 12px 25px;
                      text-decoration: none;
                      border-radius: 4px;
                      display: inline-block;">
                Set Up Password
            </a>
        </div>

        <p>Or copy and paste this link in your browser:</p>
        <p style="word-break: break-all;">{{ $setupUrl }}</p>

        <p>If you didn't request this, please ignore this email.</p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="color: #666; font-size: 12px;">This is an automated email, please do not reply.</p>
    </div>
</body>

</html>