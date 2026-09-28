<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kërkesë e re për diagnostikim baterie</title>
</head>
<body>
    <h2>Kërkesë e re për diagnostikim baterie</h2>
    <p><strong>Klienti:</strong> {{ $booking->customer_name }}</p>
    <p><strong>Kompania:</strong> {{ $booking->company_name ?: '—' }}</p>
    <p><strong>Email:</strong> {{ $booking->email }}</p>
    <p><strong>Telefon:</strong> {{ $booking->phone }}</p>
    <p><strong>Sektori:</strong> {{ $booking->sector }}</p>
    <p><strong>Lloji i baterisë:</strong> {{ $booking->battery_type }}</p>
    <p><strong>Data e preferuar:</strong> {{ $booking->preferred_date?->format('d.m.Y') }}</p>
    <p><strong>Shërbimi i kërkuar:</strong> {{ $booking->service_preference }}</p>
    <p><strong>Statusi:</strong> {{ $booking->status }}</p>

    @if ($booking->notes)
        <h3>Shënime</h3>
        <p>{{ $booking->notes }}</p>
    @endif
</body>
</html>
