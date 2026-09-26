@props([
    'title',
    'application',
    'product',
    'features' => [],
])

<div class="group rounded-[2rem] border border-slate-200 bg-white p-6 shadow-[0_20px_45px_-35px_rgba(15,23,42,0.55)] transition duration-200 hover:-translate-y-1 hover:shadow-[0_30px_60px_-30px_rgba(16,185,129,0.45)]">
    <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M4 9h16M7 18h10a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z" />
        </svg>
    </div>

    <h3 class="text-2xl font-black tracking-tight text-slate-900">{{ $title }}</h3>
    <p class="mt-3 text-sm font-medium uppercase tracking-[0.18em] text-emerald-700">{{ $application }}</p>

    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Produkt</div>
        <div class="mt-2 text-lg font-bold text-slate-900">{{ $product }}</div>
    </div>

    <ul class="mt-5 space-y-3 text-sm text-slate-700">
        @foreach ($features as $feature)
            <li class="flex items-start gap-3">
                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 3" />
                    </svg>
                </span>
                <span>{{ $feature }}</span>
            </li>
        @endforeach
    </ul>
</div>
