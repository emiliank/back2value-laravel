<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New B2B Maintenance Inquiry</title>
</head>
<body>
    <h2>New B2B Maintenance Inquiry</h2>
    <p><strong>Company:</strong> {{ $companyName }}</p>
    <p><strong>Contact:</strong> {{ $contactName }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Phone:</strong> {{ $phone }}</p>
    <p><strong>Sector:</strong> {{ $sector }}</p>
    <p><strong>Fleet size:</strong> {{ $fleetSize }}</p>

    <h3>Requirements</h3>
    <p>{{ $requirements }}</p>

    @if ($message)
        <h3>Message</h3>
        <p>{{ $message }}</p>
    @endif
</body>
</html>
