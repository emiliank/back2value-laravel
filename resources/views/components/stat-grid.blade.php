@props(['stats' => []])

<section id="pse-us" class="bg-slate-950 py-20 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-400">Pse Back2Value</p>
            <h2 class="mt-4 text-3xl font-black tracking-tight text-white sm:text-5xl">Partneri lokal i besuar për bateri industriale</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <div class="rounded-[1.75rem] border border-slate-800 bg-slate-900/70 p-6 text-center">
                    <div class="text-4xl font-black text-emerald-400 sm:text-5xl">{{ $stat['value'] }}</div>
                    <div class="mt-3 text-sm font-semibold uppercase tracking-[0.18em] text-slate-300">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
