<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use App\Http\Requests\DiagnosticBookingRequest;
use App\Mail\DiagnosticBookingMail;
use App\Models\DiagnosticBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DiagnosticBookingController extends Controller
{
    public function create(SiteContentRepository $content): View
    {
        return view('diagnostics.create', [
            'page' => $content->section('diagnostics_page'),
            'dropOffPoints' => $content->section('drop_off_points'),
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
            ->with('status', site('diagnostics_page', 'success_message', 'Kërkesa u dërgua me sukses.'));
    }
}
