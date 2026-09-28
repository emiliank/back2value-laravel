@props(['section' => null])

<section id="kalkulator" class="calculator" data-savings-calculator>
    <div class="section-shell">
        <div class="section-intro section-intro--small">
            <span class="eyebrow">VLERËSIM I SHPEJTË</span>
            <h2>Sa vlen rigjenerimi për bankën tuaj të baterive?</h2>
            <p>Vendosni numrin dhe peshën mesatare të baterive për të vlerësuar materialin e rikuperuar dhe uljen e kostos ndaj zëvendësimit.</p>
        </div>

        <div class="calculator-panel">
            <div class="calculator-fields">
                <label class="field">
                    <span class="field__label">Numri i baterive</span>
                    <input class="field__input" type="number" min="1" max="5000" step="1" value="24" data-battery-count inputmode="numeric">
                </label>

                <label class="field">
                    <span class="field__label">Pesha mesatare e një baterie (kg)</span>
                    <input class="field__input" type="number" min="1" max="3000" step="1" value="32" data-battery-weight inputmode="numeric">
                </label>
            </div>

            <div class="calculator-results" aria-live="polite">
                <div class="calculator-result">
                    <strong>50–100%</strong>
                    <span>Kapacitet i rikthyer me rigjenerim</span>
                </div>
                <div class="calculator-result">
                    <strong>−55%</strong>
                    <span>Kosto ndaj zëvendësimit me bateri të re</span>
                </div>
                <div class="calculator-result">
                    <strong><span data-result-lead>0</span> kg</strong>
                    <span>Plumb i rikuperuar për riciklim të licencuar</span>
                </div>
                <div class="calculator-result">
                    <strong><span data-result-co2>0</span> kg</strong>
                    <span>CO₂ e shmangur nga prodhimi i baterive të re</span>
                </div>
            </div>

            <p class="calculator-note">
                Vlerësimi bazohet në përvojën tonë teknike: rikthim 50–100% i kapacitetit, ulje mesatare 55% e kostos ndaj
                zëvendësimit, përmbajtje plumbi rreth 55% e peshës dhe shmangie e rreth 1.6 kg CO₂ për kilogram plumbi të
                rikuperuar. Shifrat janë indikative — rezultati real përcaktohet pas diagnostikimit teknik.
            </p>

            <div class="calculator-actions">
                <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
                <a class="secondary-cta" href="{{ route('sustainability.index') }}">Shiko ndikimin mjedisor</a>
            </div>
        </div>
    </div>
</section>
