<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use App\Http\Requests\DiagnosticBookingRequest;
use App\Mail\DiagnosticBookingMail;
use App\Models\DiagnosticBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class DiagnosticBookingController extends Controller
{
    public function create(SiteContentRepository $content): Response
    {
        $page = $content->section('diagnostics_page');

        return Inertia::render('Public/Diagnostics', [
            'page' => $page,
            'dropOffPoints' => $content->section('drop_off_points'),
            'activeNav' => 'diagnostics',
            'title' => $page['meta_title'] ?? 'Rezervo Diagnostikim | Back2Value',
            'description' => $page['meta_description'] ?? '',
            'today' => now()->toDateString(),
        ]);
    }

    public function store(DiagnosticBookingRequest $request): RedirectResponse
    {
        $booking = DiagnosticBooking::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        Mail::to('emiliank18@gmail.com')->send(new DiagnosticBookingMail($booking));

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
