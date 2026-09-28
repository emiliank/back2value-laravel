<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Report</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; color: #0f172a; margin: 0; padding: 32px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0;">
        <h2 style="margin: 0 0 16px; color: #0f172a;">Battery Diagnostic Report</h2>

        <p style="margin: 0 0 16px; line-height: 1.6;">
            A diagnostic report was created and it has been marked as eligible for reactivation.
        </p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #334155;">Report ID</td>
                <td style="padding: 8px 0; color: #0f172a;">#{{ $report->id }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #334155;">Battery ID</td>
                <td style="padding: 8px 0; color: #0f172a;">{{ $report->battery_id }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #334155;">Actual Capacity</td>
                <td style="padding: 8px 0; color: #0f172a;">{{ $report->actual_capacity }} Ah</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #334155;">Expected Capacity</td>
                <td style="padding: 8px 0; color: #0f172a;">{{ $report->expected_capacity }} Ah</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #334155;">Eligibility</td>
                <td style="padding: 8px 0; color: #0f172a;">{{ $report->is_reactivation_eligible ? 'Eligible' : 'Not eligible' }}</td>
            </tr>
        </table>

        @if ($report->notes)
            <p style="margin: 0; line-height: 1.6; color: #475569;">
                <strong>Notes:</strong><br>
                {{ $report->notes }}
            </p>
        @endif
    </div>
</body>
</html>
