<?php

namespace App\Http\Controllers;

use App\Support\Locale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Sends visitors arriving on the bare root URL to their own language.
 */
class LocaleRedirectController extends Controller
{
    public function __construct(private Locale $locales) {}

    public function __invoke(Request $request): RedirectResponse
    {
        $locale = $this->locales->current();

        $this->locales->remember($locale);

        return redirect()->to(route('home', ['locale' => $locale]));
    }
}
