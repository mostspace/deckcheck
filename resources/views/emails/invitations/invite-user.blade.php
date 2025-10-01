<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>You're Invited to Join {{ $invitation->boarding->vessel->name }}</title>
</head>

<body
    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f4; padding: 2rem;">
    <div
        style="max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">

        <h2 style="color: #6840c6; margin-bottom: 1rem;">You're Invited!</h2>

        <p>Hello,</p>

        <p>
            You've been invited to join the vessel <strong>{{ $invitation->boarding->vessel->name }}</strong> as a
            <strong>{{ ucfirst($invitation->boarding->access_level) }}</strong>.
        </p>

        <p>To accept this invitation and complete your account setup, click the link below:</p>

        <p style="margin: 2rem 0;">
            <a href="{{ $acceptUrl }}"
                style="background-color: #6840c6; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; display: inline-block;">
                Accept Invitation
            </a>
        </p>

        <p>If you weren’t expecting this invitation, you can safely ignore this message.</p>

        <p style="margin-top: 2rem;">Thanks,<br>The {{ config('app.name') }} Team</p>
    </div>
</body>

</html>
