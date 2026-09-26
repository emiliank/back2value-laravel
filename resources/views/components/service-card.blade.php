@props([
    'title',
    'description',
])

<div class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-[0_15px_35px_-30px_rgba(15,23,42,0.6)] transition duration-200 hover:border-emerald-200 hover:shadow-[0_18px_40px_-30px_rgba(16,185,129,0.55)]">
    <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    </div>

    <h3 class="text-xl font-black tracking-tight text-slate-900">{{ $title }}</h3>
    <p class="mt-3 text-base leading-7 text-slate-600">{{ $description }}</p>
</div>
