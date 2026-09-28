@extends('layouts.public')

@section('title', 'Katalogu i Produkteve | Back2Value - Bateri Gjermane')
@section('meta_description', 'Katalogu zyrtar i produkteve RID-Batterie dhe zgjidhjeve partnere në Shqipëri. Bateri industriale, UPS, traksionare dhe solare me çmime me kërkesë.')

@section('content')
    @php
        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp'] ?? '355692734476');
        $defaultWhatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode('Përshëndetje! Po interesohem për një ofertë për bateri industriale nga katalogu.');
    @endphp

    <div class="products-catalog-page pb-20">
            <!-- Catalog Hero -->
            <section class="border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white py-14">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-10 lg:grid-cols-[1.2fr_0.8fr]">
                        <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-1 text-xs font-bold tracking-widest text-emerald-800 uppercase border border-emerald-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Portofoli Zyrtar · RID Battery Gjermani
                        </span>
                        <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
                            Bateri Industriale me Kategori
                        </h1>
                        <p class="mt-4 text-lg leading-relaxed text-slate-600">
                            Portofoli i plotë sipas katalogut teknik gjerman të <strong>RID-Batterie GmbH</strong> dhe partnerëve të autorizuar Hoppecke &amp; Socomec. Zgjidhje të testuara për sisteme diellore, telekomunikacion, UPS, flota kamionësh dhe pirunë industrialë.
                        </p>
                        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-slate-700 bg-emerald-50/70 border border-emerald-100 rounded-xl p-3.5">
                            <div class="flex items-center gap-2 font-medium text-emerald-900">
                                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Të gjitha çmimet ofrohen <strong>me kërkesë (Request a Quote)</strong> sipas vëllimit dhe nevojave tuaja teknike.</span>
                            </div>
                        </div>
                        </div>

                        <div class="relative">
                            <div class="grid grid-cols-2 gap-3">
                                <img
                                    src="{{ asset('images/battery-start.jpg') }}"
                                    alt="Bateri startimi RID-Batterie për automjete dhe kamionë"
                                    width="380"
                                    height="260"
                                    class="col-span-2 h-44 w-full rounded-2xl object-cover shadow-sm ring-1 ring-slate-200"
                                >
                                <img
                                    src="{{ asset('images/battery-ups.jpg') }}"
                                    alt="Bateri të gjelbra RID për sisteme UPS dhe përdorim industrial"
                                    width="380"
                                    height="240"
                                    class="h-32 w-full rounded-2xl object-cover shadow-sm ring-1 ring-slate-200"
                                    loading="lazy"
                                >
                                <img
                                    src="{{ asset('images/battery-pzs.jpg') }}"
                                    alt="Bateri traksionare RID me qeliza industriale dhe kapakë portokalli"
                                    width="250"
                                    height="240"
                                    class="h-32 w-full rounded-2xl object-cover shadow-sm ring-1 ring-slate-200"
                                    loading="lazy"
                                >
                            </div>
                            <div class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-slate-800 shadow-md ring-1 ring-slate-200">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                RID-Batterie GmbH · Bateri Gjermane
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters Bar -->
                    <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $searchQuery ?? '' }}"
                                    placeholder="Kërko sipas modelit, kapacitetit (Ah), teknologjisë ose markës..."
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition"
                                >
                            </div>

                            @if (!empty($activeCategory))
                                <input type="hidden" name="category" value="{{ $activeCategory }}">
                            @endif

                            @if (!empty($activeApplication))
                                <input type="hidden" name="application" value="{{ $activeApplication }}">
                            @endif

                            @foreach ($vehicleQuery as $vehicleKey => $vehicleValue)
                                <input type="hidden" name="{{ $vehicleKey }}" value="{{ $vehicleValue }}">
                            @endforeach

                            <div class="flex items-center gap-3">
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
                                    Kërko
                                </button>
                                @if (filled($searchQuery) || filled($activeCategory) || filled($activeApplication) || !empty($vehicleProfile))
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                                        Pastro
                                    </a>
                                @endif
                            </div>
                        </form>

                        <!-- Category Filter Pills -->
                        @if (!empty($allCategories))
                            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-2 scrollbar-thin">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider shrink-0 mr-1">Kategoritë:</span>
                                <a
                                    href="{{ route('products.index', array_filter(['q' => $searchQuery, 'application' => $activeApplication])) }}"
                                    class="shrink-0 rounded-full px-3.5 py-1 text-xs font-semibold transition {{ empty($activeCategory) ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                                >
                                    Të gjitha
                                </a>
                                @foreach ($allCategories as $cat)
                                    <a
                                        href="{{ route('products.index', array_filter(['category' => $cat, 'q' => $searchQuery, 'application' => $activeApplication])) }}"
                                        class="shrink-0 rounded-full px-3.5 py-1 text-xs font-semibold transition {{ $activeCategory === $cat ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                                    >
                                        {{ $cat }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Application Filter Pills -->
                        @if (!empty($applicationFilters))
                            <div class="mt-3 flex items-center gap-2 overflow-x-auto pb-2 scrollbar-thin">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider shrink-0 mr-1">Aplikimi:</span>
                                <a
                                    href="{{ route('products.index', array_filter(['q' => $searchQuery, 'category' => $activeCategory])) }}"
                                    class="shrink-0 rounded-full px-3.5 py-1 text-xs font-semibold transition {{ empty($activeApplication) ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                                >
                                    Të gjitha
                                </a>
                                @foreach ($applicationFilters as $key => $filter)
                                    <a
                                        href="{{ route('products.index', array_filter(['application' => $key, 'q' => $searchQuery, 'category' => $activeCategory])) }}"
                                        class="shrink-0 rounded-full px-3.5 py-1 text-xs font-semibold transition {{ $activeApplication === $key ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                                    >
                                        {{ $filter['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Vehicle Finder -->
            <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-10">
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 sm:p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-lg font-extrabold text-slate-900">Gjej baterinë për mjetin tënd</h2>
                                <p class="text-sm text-slate-500">Zgjidhni tipin, markën, modelin dhe vitin — ose jepni VIN-in dhe kapacitetin aktual — dhe ju tregojmë përshtatjen më të mirë nga seritë e baterive tona.</p>
                            </div>
                        </div>
                        <img
                            src="{{ asset('images/battery-start.jpg') }}"
                            alt=""
                            aria-hidden="true"
                            width="128"
                            height="88"
                            loading="lazy"
                            class="hidden h-[88px] w-32 shrink-0 rounded-xl object-cover ring-1 ring-slate-200 sm:block"
                        >
                    </div>

                    <form method="GET" action="{{ route('products.index') }}" class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @if (filled($searchQuery))
                            <input type="hidden" name="q" value="{{ $searchQuery }}">
                        @endif

                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tipi i mjetit</span>
                            <select name="v_type" class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition">
                                <option value="">Zgjidhni tipin</option>
                                @foreach ($vehicleOptions['types'] as $typeKey => $typeLabel)
                                    <option value="{{ $typeKey }}" @selected(($vehicleProfile['type'] ?? '') === $typeKey)>{{ $typeLabel }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Marka</span>
                            <select name="v_make" class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition">
                                <option value="">Zgjidhni markën</option>
                                @foreach ($vehicleOptions['makes'] as $makeName)
                                    <option value="{{ $makeName }}" @selected(($vehicleProfile['make'] ?? '') === $makeName)>{{ $makeName }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Modeli</span>
                            <input
                                type="text"
                                name="v_model"
                                value="{{ $vehicleProfile['model'] ?? '' }}"
                                maxlength="60"
                                placeholder="p.sh. Sprinter, Golf, A4..."
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition"
                            >
                        </label>
                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Viti i prodhimit</span>
                            <select name="v_year" class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition">
                                <option value="">Zgjidhni vitin</option>
                                @foreach ($vehicleOptions['years'] as $yearValue)
                                    <option value="{{ $yearValue }}" @selected(($vehicleProfile['year'] ?? '') === (string) $yearValue)>{{ $yearValue }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Karburanti</span>
                            <select name="v_fuel" class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition">
                                <option value="">Zgjidhni karburantin</option>
                                @foreach ($vehicleOptions['fuels'] as $fuelKey => $fuelLabel)
                                    <option value="{{ $fuelKey }}" @selected(($vehicleProfile['fuel'] ?? '') === $fuelKey)>{{ $fuelLabel }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Sistemi Start-Stop</span>
                            <select name="v_startstop" class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition">
                                <option value="">Zgjidhni</option>
                                @foreach ($vehicleOptions['start_stop'] as $startStopKey => $startStopLabel)
                                    <option value="{{ $startStopKey }}" @selected(($vehicleProfile['startstop'] ?? '') === $startStopKey)>{{ $startStopLabel }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Kapaciteti aktual (Ah)</span>
                            <input
                                type="number"
                                name="v_capacity"
                                value="{{ $vehicleProfile['capacity'] ?? '' }}"
                                min="10"
                                max="1000"
                                placeholder="p.sh. 74"
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition"
                            >
                        </label>
                        <label class="block">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">VIN (17 karaktere)</span>
                            <input
                                type="text"
                                name="vin"
                                value="{{ $vehicleProfile['vin'] ?? '' }}"
                                maxlength="17"
                                placeholder="p.sh. WVWZZZ1KZAW000001"
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm uppercase tracking-wider text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition"
                            >
                        </label>

                        <label class="block sm:col-span-2 lg:col-span-3">
                            <span class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Detaje të tjera (opsional)</span>
                            <input
                                type="text"
                                name="v_notes"
                                value="{{ $vehicleProfile['notes'] ?? '' }}"
                                maxlength="300"
                                placeholder="p.sh. motori 2.0 TDI, përdorim në ndërtim, bateria aktuale 5 vjet e vjetër..."
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200 transition"
                            >
                        </label>

                        <div class="flex flex-wrap items-center gap-3 sm:col-span-2 lg:col-span-4">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold uppercase tracking-wider text-white shadow-sm hover:bg-emerald-700 active:scale-[0.98] transition">
                                Gjej përshtatjen më të mirë
                            </button>
                            @if (!empty($vehicleProfile))
                                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                                    Pastro kërkimin e mjetit
                                </a>
                            @endif
                            <span class="text-xs text-slate-400">Kërkimi i mjetit shfaq bateritë e serisë RID ST për startim.</span>
                        </div>


                    </form>

                    @if (!empty($vehicleProfile))
                        @php
                            $vehicleRecommendation = $vehicleProfile['recommendation'];
                            $vehicleVinInfo = $vehicleProfile['vin_info'];
                        @endphp
                        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50/70 p-4 sm:p-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="min-w-0 space-y-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center rounded-full bg-emerald-600 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-white">Kërkim mjeti</span>
                                        @if ($vehicleProfile['summary'] !== '')
                                            <span class="text-sm font-bold text-slate-800">{{ $vehicleProfile['summary'] }}</span>
                                        @endif
                                    </div>

                                    @if (($vehicleVinInfo['state'] ?? '') === 'valid')
                                        <p class="text-sm text-slate-600">
                                            <span class="font-semibold text-slate-800">VIN i vlefshëm</span>
                                            — Rajoni: {{ $vehicleVinInfo['region'] }}
                                            @if (!empty($vehicleVinInfo['year_candidates']))
                                                · Viti i modelit (kodit): {{ implode(' / ', $vehicleVinInfo['year_candidates']) }}
                                            @endif
                                            @if (!empty($vehicleVinInfo['year_matches_selection']))
                                                — <span class="font-medium text-emerald-700">përputhet me vitin e zgjedhur</span>
                                            @endif
                                        </p>
                                    @elseif (($vehicleVinInfo['state'] ?? '') === 'invalid')
                                        <p class="text-sm text-amber-700">
                                            <span class="font-semibold">{{ $vehicleVinInfo['message'] }}</span> Kontrolloni VIN-in dhe provoni përsëri.
                                        </p>
                                    @endif

                                    <p class="text-sm text-slate-600">
                                        <span class="font-semibold text-slate-800">Rekomandim: {{ $vehicleRecommendation['label'] }} · {{ $vehicleRecommendation['capacity_label'] }}</span>
                                        — {{ $vehicleRecommendation['reason'] }}
                                    </p>

                                    @if (!empty($vehicleProfile['match_message']))
                                        <p class="text-sm font-medium text-emerald-700">{{ $vehicleProfile['match_message'] }}</p>
                                    @endif
                                </div>

                                <div class="flex shrink-0 flex-wrap gap-3">
                                    <a
                                        href="{{ $vehicleProfile['whatsapp_url'] }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 active:scale-[0.98] transition"
                                    >
                                        Konfirmo përshtatjen me VIN
                                        <span aria-hidden="true">&rarr;</span>
                                    </a>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                                        Pastro
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Product Groups by Category -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-12">
                @if ($groupedProducts->isEmpty())
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-14 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">Nuk u gjet asnjë produkt</h2>
                        <p class="mt-2 text-sm text-slate-500">Provoni të kërkoni me fjalë të tjera ose pastroni filtrat e kategorive.</p>
                        <a href="{{ route('products.index') }}" class="mt-5 inline-flex rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                            Shfaq të gjitha bateritë
                        </a>
                    </div>
                @else
                    @foreach ($groupedProducts as $categoryName => $products)
                        <section class="mb-16 scroll-mt-28" id="kategoria-{{ \Illuminate\Support\Str::slug($categoryName) }}">
                            <div class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b border-slate-200 pb-4">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ asset($categoryImages[$categoryName]['image']) }}"
                                            alt="{{ $categoryImages[$categoryName]['alt'] }}"
                                            width="80"
                                            height="56"
                                            loading="lazy"
                                            class="h-14 w-20 shrink-0 rounded-lg object-cover ring-1 ring-slate-200"
                                        >
                                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $categoryName }}</h2>
                                        <span class="rounded-full bg-emerald-100 px-3 py-0.5 text-xs font-bold text-emerald-800">
                                            {{ $products->count() }} {{ $products->count() === 1 ? 'model' : 'modele' }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-500">
                                        @if (str_contains($categoryName, 'OPzS'))
                                            Bateri tubulare vented 2V me jetëgjatësi 20 vjet për aplikime fotovoltaike dhe industriale.
                                        @elseif (str_contains($categoryName, 'OPzV'))
                                            Bateri me xhel pa mirëmbajtje sipas standardeve DIN për telekomunikacion dhe fshatra energjetikë.
                                        @elseif (str_contains($categoryName, 'Xtreme'))
                                            Teknologji Pure Lead AGM (99.99%) me shkarkim të shpejtë për UPS, data centers dhe banka.
                                        @elseif (str_contains($categoryName, 'ST'))
                                            Bateri startimi komerciale (Heavy-Duty, EFB, AGM, Marine) për kamionë dhe makineri të rënda.
                                        @elseif (str_contains($categoryName, 'Motive Power'))
                                            Qeliza traksionare 2V PzS (50-150 Ah/Plate) për pirunë elektrikë dhe makineri magazinash.
                                        @elseif (str_contains($categoryName, 'FNC'))
                                            Teknologji nikel-kadmium me fibra për temperatura ekstreme (-50°C deri +60°C) dhe 3000+ cikle.
                                        @elseif (str_contains($categoryName, 'GroE'))
                                            Bateri Planté me plumb të pastër me jetëgjatësi 25 vjet për centrale dhe nënstacione energjie.
                                        @elseif (str_contains($categoryName, 'OGi'))
                                            Aftësi shumë e lartë për rryma të forta, jetëgjatësi deri në 18 vjet për hekurudha dhe UPS.
                                        @elseif (str_contains($categoryName, 'Power Cell'))
                                            Kontejner i integruar ruajtjeje energjie 15 kVA me qeliza NiCd dhe inverter Sierra 25-48.
                                        @elseif (str_contains($categoryName, 'Diagnostics'))
                                            Pajisje profesionale laboratorike për testimin, regjistrimin dhe rigjenerimin e baterive.
                                        @elseif (str_contains($categoryName, 'Socomec'))
                                            Sisteme të plota UPS për energji emergjence, ndriçim evakuimi dhe mbrojtje nga zjarri.
                                        @else
                                            Zgjidhje profesionale për ruajtje dhe furnizim të pandërprerë energjie.
                                        @endif
                                    </p>
                                </div>

                                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode('Përshëndetje! Po kërkoj ofertë për kategorinë: ' . $categoryName) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition">
                                    <span>Kërko ofertë për këtë kategori</span>
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>

                            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($products as $product)
                                    <article class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md hover:border-emerald-300">
                                        <div>
                                            <img
                                                src="{{ asset($categoryImages[$categoryName]['image']) }}"
                                                alt=""
                                                aria-hidden="true"
                                                width="336"
                                                height="112"
                                                loading="lazy"
                                                class="mb-4 h-28 w-full rounded-xl object-cover ring-1 ring-slate-100"
                                            >
                                            <!-- Top tags -->
                                            <div class="flex items-center justify-between gap-2 mb-3">
                                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 tracking-wide">
                                                    {{ $product['brand'] }}
                                                </span>
                                                <div class="flex items-center gap-1.5">
                                                    @if (filled($product['voltage']))
                                                        <span class="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700 border border-blue-200">
                                                            {{ $product['voltage'] }}
                                                        </span>
                                                    @endif
                                                    <span class="rounded-md bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 border border-emerald-200">
                                                        {{ $product['capacity_ah'] }} Ah
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Product Title -->
                                            <h3 class="text-xl font-bold text-slate-900 tracking-tight leading-snug">
                                                {{ $product['title'] }}
                                            </h3>

                                            @if (filled($product['technology']))
                                                <p class="mt-1 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                                    {{ $product['technology'] }}
                                                </p>
                                            @endif

                                            <!-- Description -->
                                            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                                                {{ $product['description'] }}
                                            </p>

                                            <!-- Technical Specs -->
                                            @if (!empty($product['specs']))
                                                <div class="mt-4 rounded-xl bg-slate-50 p-3 text-xs text-slate-600 border border-slate-100 space-y-1.5">
                                                    @if (!empty($product['specs']['dimensions']))
                                                        <div class="flex justify-between gap-2">
                                                            <span class="text-slate-400 font-medium">Përmasat:</span>
                                                            <span class="font-semibold text-slate-800 text-right">{{ $product['specs']['dimensions'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if (!empty($product['specs']['weight']))
                                                        <div class="flex justify-between gap-2">
                                                            <span class="text-slate-400 font-medium">Pesha:</span>
                                                            <span class="font-semibold text-slate-800">{{ $product['specs']['weight'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if (!empty($product['specs']['cca']))
                                                        <div class="flex justify-between gap-2">
                                                            <span class="text-slate-400 font-medium">Fuqia (CCA):</span>
                                                            <span class="font-bold text-emerald-700">{{ $product['specs']['cca'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if (!empty($product['specs']['design_life']))
                                                        <div class="flex justify-between gap-2">
                                                            <span class="text-slate-400 font-medium">Jetëgjatësia:</span>
                                                            <span class="font-semibold text-slate-800">{{ $product['specs']['design_life'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if (!empty($product['specs']['cycles']))
                                                        <div class="flex justify-between gap-2">
                                                            <span class="text-slate-400 font-medium">Ciklet:</span>
                                                            <span class="font-semibold text-slate-800">{{ $product['specs']['cycles'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Footer card: NO PRICE SHOWN, quote request instead -->
                                        <div class="mt-6 border-t border-slate-100 pt-4">
                                            <div class="mb-3 flex items-center justify-between text-xs text-slate-500">
                                                <span class="flex items-center gap-1 text-emerald-700 font-medium">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Garanci: {{ $product['warranty_months'] }} muaj
                                                </span>
                                                <span class="font-semibold uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                                    Çmimi me kërkesë
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <a
                                                    href="{{ $product['quote_url'] }}"
                                                    target="_blank"
                                                    rel="noopener"
                                                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm hover:bg-emerald-700 active:scale-[0.98] transition"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    Kërko Ofertë
                                                </a>
                                                <a
                                                    href="{{ route('home') }}#kontakt"
                                                    title="Kontakto me email ose telefon"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                @endif
            </div>

            <!-- Bottom Inquiry CTA -->
            <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-12">
                <div class="rounded-3xl bg-slate-900 p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
                    <div class="max-w-2xl relative z-10">
                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Konsulencë Teknike B2B</span>
                        <h2 class="mt-3 text-3xl font-extrabold sm:text-4xl">Keni nevojë për dimensionim apo ofertë të personalizuar?</h2>
                        <p class="mt-4 text-slate-300 text-base leading-relaxed">
                            Ekipi ynë teknik në Pogradec llogarit kapacitetet e kërkuara, përzgjedh teknologjinë optimale (OPzS, OPzV, AGM apo NiCd) dhe harton ofertën zyrtare me kushte partneriteti.
                        </p>
                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ $defaultWhatsappUrl }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-emerald-600 transition">
                                <span>Bisedo në WhatsApp</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <a href="{{ route('home') }}#kontakt" class="inline-flex items-center gap-2 rounded-xl border border-slate-700 bg-slate-800/80 px-6 py-3 text-sm font-bold text-white hover:bg-slate-800 transition">
                                <span>Formulari i Kontaktit</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
@endsection
