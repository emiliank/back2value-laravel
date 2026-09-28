<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticBookingRequest;
use App\Mail\DiagnosticBookingMail;
use App\Models\DiagnosticBooking;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DiagnosticBookingController extends Controller
{
    public function create(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('diagnostics.create', [
            'settings' => $content['settings'],
            'page' => $content['diagnostics_page'],
            'dropOffPoints' => $content['drop_off_points'],
            'activeNav' => 'diagnostics',
        ]);
    }

    public function store(DiagnosticBookingRequest $request): RedirectResponse
    {
        $booking = DiagnosticBooking::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        Mail::to('sales@back2value.com')->send(new DiagnosticBookingMail($booking));

        Log::info('Battery diagnostic booking received.', [
            'booking_id' => $booking->id,
            'sector' => $booking->sector,
            'battery_type' => $booking->battery_type,
            'preferred_date' => $booking->preferred_date?->toDateString(),
        ]);

        return redirect()
            ->route('diagnostics.create')
            ->with('status', 'Kërkesa u dërgua me sukses. Ekipi teknik do t\'ju kontaktojë brenda 24 orëve.');
    }
}
