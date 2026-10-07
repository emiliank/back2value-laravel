<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiagnosticBookingValidationLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_messages_follow_the_requested_language(): void
    {
        $albanian = $this->postJson(route('diagnostics.bookings.store', ['locale' => 'sq']), [])
            ->json('errors.customer_name.0');

        $english = $this->postJson(route('diagnostics.bookings.store', ['locale' => 'en']), [])
            ->json('errors.customer_name.0');

        $this->assertSame('Fusha emri dhe mbiemri është e detyrueshme.', $albanian);
        $this->assertSame('The customer name field is required.', $english);
    }
}
