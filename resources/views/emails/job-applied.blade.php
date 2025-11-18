{{-- resources/views/emails/job-applied.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px;">
        <h1 style="color: #007bff; text-align: center;">Application Submitted Successfully!</h1>
        <p>Dear {{ $user->name }},</p>
        <p>Thank you for applying to the position of <strong>{{ $job->title }}</strong>.</p>
        <p><strong>Application Details:</strong></p>
        <ul>
            <li>Job Title: {{ $job->title }}</li>
            <li>Application ID: #{{ $application->id }}</li>
            <li>Submitted on: {{ $application->created_at->format('F j, Y') }}</li>
        </ul>
        <p>Your application has been received and is under review. We will get back to you soon.</p>
        <p>Your CV is attached to this email. You can also download it here: <a href="{{ url('/applications/' . $application->id . '/download-cv') }}" style="color: #007bff;">Download CV</a></p>
        <p>If you have any questions, contact us.</p>
        <p>Best regards,<br>The Recruitment Team<br>Lamarin</p>
        <hr style="border: none; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #666; text-align: center;">&copy; 2025 Lamarin Company. This is an automated email.</p>
    </div>
</body>
</html>
