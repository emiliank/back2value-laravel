<?php

namespace App\Services;

use App\Models\Battery;
use Illuminate\Support\Collection;

class VehicleFitmentService
{
    /**
     * The starter-battery category the vehicle finder searches in.
     */
    public const STARTER_CATEGORY = 'RID ST Series (Commercial Vehicles)';

    /**
     * Query-string keys that activate the vehicle finder.
     *
     * @var list<string>
     */
    public const QUERY_KEYS = ['v_type', 'v_make', 'v_model', 'v_year', 'v_fuel', 'v_startstop', 'v_capacity', 'v_notes', 'vin'];

    public const TYPES = [
        'car' => 'Makinë personale',
        'van' => 'Furgon / Pickup',
        'truck' => 'Kamion',
        'bus' => 'Autobus',
        'other' => 'Tjetër',
    ];

    public const FUELS = [
        'petrol' => 'Benzinë',
        'diesel' => 'Naftë',
        'hybrid' => 'Hibrid',
        'electric' => 'Elektrik',
    ];

    public const START_STOP = [
        'yes' => 'Po',
        'no' => 'Jo',
        'unknown' => 'Nuk e di',
    ];

    public const MAKES = [
        'Mercedes-Benz',
        'BMW',
        'Volkswagen',
        'Audi',
        'Opel',
        'Ford',
        'Renault',
        'Peugeot',
        'Citroën',
        'Toyota',
        'Hyundai',
        'Kia',
        'Fiat',
        'Škoda',
        'Volvo',
        'Seat',
        'Dacia',
        'Mitsubishi',
        'Nissan',
        'Honda',
        'Mazda',
        'Suzuki',
        'Isuzu',
        'MAN',
        'Scania',
        'DAF',
        'Iveco',
        'JCB',
        'Tjetër',
    ];

    /**
     * Default recommended capacity bands (Ah) per vehicle type when the
     * owner does not provide the capacity of the battery being replaced.
     *
     * @var array<string, array{0: int, 1: int}>
     */
    private const CAPACITY_BANDS = [
        'car' => [40, 80],
        'van' => [60, 110],
        'truck' => [100, 225],
        'bus' => [150, 225],
        'other' => [25, 225],
    ];

    /**
     * Static options powering the finder form selects.
     *
     * @return array<string, mixed>
     */
    public function options(): array
    {
        return [
            'types' => self::TYPES,
            'makes' => self::MAKES,
            'fuels' => self::FUELS,
            'start_stop' => self::START_STOP,
            'years' => $this->years(),
        ];
    }

    /**
     * @return list<int>
     */
    public function years(): array
    {
        return range(((int) date('Y')) + 1, 1990);
    }

    /**
     * The finder is active when at least one vehicle parameter was submitted.
     *
     * @param  array<string, mixed>  $query
     */
    public function isActive(array $query): bool
    {
        foreach (self::QUERY_KEYS as $key) {
            if (filled($query[$key] ?? null)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vehicle params present in the query, for preserving the finder state
     * across search and category-pill links.
     *
     * @param  array<string, mixed>  $query
     * @return array<string, string>
     */
    public function queryState(array $query): array
    {
        $state = [];

        foreach (self::QUERY_KEYS as $key) {
            if (filled($query[$key] ?? null)) {
                $state[$key] = (string) $query[$key];
            }
        }

        return $state;
    }

    /**
     * Build the sanitized vehicle profile with recommendation and WhatsApp CTA.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function profile(array $input, string $whatsappNumber): array
    {
        $clean = fn (string $key, int $max = 80): string => mb_substr(trim((string) ($input[$key] ?? '')), 0, $max);

        $type = $clean('v_type', 20);
        $type = array_key_exists($type, self::TYPES) ? $type : '';

        $fuel = $clean('v_fuel', 20);
        $fuel = array_key_exists($fuel, self::FUELS) ? $fuel : '';

        $startStop = $clean('v_startstop', 20);
        $startStop = array_key_exists($startStop, self::START_STOP) ? $startStop : '';

        $year = $clean('v_year', 10);
        $year = ctype_digit($year) && (int) $year >= 1900 && (int) $year <= ((int) date('Y')) + 1 ? $year : '';

        $capacityRaw = $clean('v_capacity', 10);
        $capacity = ctype_digit($capacityRaw) && (int) $capacityRaw >= 10 && (int) $capacityRaw <= 1000
            ? (int) $capacityRaw
            : null;

        $make = $clean('v_make');
        $model = $clean('v_model', 60);
        $notes = $clean('v_notes', 300);
        $vin = $clean('vin', 25);

        $vinInfo = $this->decodeVin($vin, $year !== '' ? (int) $year : null);
        $recommendation = $this->recommend($type, $fuel, $startStop, $capacity);

        $vehicleLine = trim(implode(' ', array_filter([$year, $make, $model])));

        $message = 'Përshëndetje! Kërkoj këshillë për baterinë të re për mjetin tim.';
        if ($vehicleLine !== '') {
            $message .= ' Mjeti: '.$vehicleLine.'.';
        }
        if ($type !== '') {
            $message .= ' Tipi: '.self::TYPES[$type].'.';
        }
        if ($fuel !== '') {
            $message .= ' Karburanti: '.self::FUELS[$fuel].'.';
        }
        if ($startStop !== '') {
            $message .= ' Start-Stop: '.self::START_STOP[$startStop].'.';
        }
        if ($capacity !== null) {
            $message .= ' Kapaciteti i baterisë aktuale: '.$capacity.' Ah.';
        }
        if (($vinInfo['state'] ?? '') === 'valid') {
            $message .= ' VIN: '.$vinInfo['vin'].'.';
        }
        if ($notes !== '') {
            $message .= ' Detaje: '.$notes.'.';
        }
        $message .= ' Rekomandimi: '.$recommendation['label'].' ('.$recommendation['capacity_label'].'). A mund të konfirmoni përshtatjen?';

        $summaryParts = array_filter([
            $year,
            trim($make.' '.$model),
            $type !== '' ? self::TYPES[$type] : '',
            $fuel !== '' ? self::FUELS[$fuel] : '',
        ]);

        return [
            'type' => $type,
            'type_label' => $type !== '' ? self::TYPES[$type] : '',
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'fuel' => $fuel,
            'fuel_label' => $fuel !== '' ? self::FUELS[$fuel] : '',
            'startstop' => $startStop,
            'startstop_label' => $startStop !== '' ? self::START_STOP[$startStop] : '',
            'capacity' => $capacity,
            'notes' => $notes,
            'vin' => ($vinInfo['state'] ?? '') === 'valid' ? $vinInfo['vin'] : $vin,
            'vin_info' => $vinInfo,
            'recommendation' => $recommendation,
            'summary' => implode(' · ', $summaryParts),
            'whatsapp_url' => 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($message),
            'match_level' => null,
            'match_message' => null,
            'result_count' => 0,
        ];
    }

    /**
     * Decide the technology family and capacity band for the given vehicle.
     *
     * @return array<string, mixed>
     */
    public function recommend(string $type, string $fuel, string $startStop, ?int $capacity): array
    {
        [$min, $max] = self::CAPACITY_BANDS[$type] ?? self::CAPACITY_BANDS['other'];
        $capacityGiven = $capacity !== null;

        if ($capacityGiven) {
            $min = max(25, $capacity - 5);
            $max = $capacity + 20;
        }

        if ($fuel === 'electric') {
            $technology = 'agm';
            $label = 'Bateri AGM (automjet elektrik)';
            $reason = 'Edhe automjetet elektrike përdorin bateri 12V për sistemet elektrike — rekomandohet AGM.';
        } elseif ($startStop === 'yes') {
            $technology = 'agm';
            $label = 'Bateri AGM me Start-Stop';
            $reason = 'Sistemi Start-Stop kërkon teknologji AGM që duron qindra cikla rikarikimi në çdo ndalim të motorit.';
        } elseif ($startStop === 'no') {
            $technology = 'heavy';
            $label = 'Bateri Heavy-Duty (standard)';
            $reason = $fuel === 'diesel'
                ? 'Për motorë naftë me kompresion të lartë rekomandohet bateri e fortë me fuqi rrotullimi të lartë — seria Heavy-Duty.'
                : 'Bateri standard e fortë për nisje të përditshme; nëse keni shumë nisje-dalje në qytet, zgjidhni EFB.';
        } else {
            $technology = null;
            $label = 'Seria e starterëve RID ST';
            $reason = 'Pa të dhëna për Start-Stop shfaqim të gjithë serinë — jepni VIN-in ose kapacitetin aktual për përshtatje më të saktë.';
        }

        return [
            'technology' => $technology,
            'label' => $label,
            'reason' => $reason,
            'capacity_min' => $min,
            'capacity_max' => $max,
            'capacity_label' => $min.'–'.$max.' Ah',
            'capacity_given' => $capacityGiven,
        ];
    }

    /**
     * Progressively narrow the starter-battery list: technology + capacity
     * first, then technology only, then capacity only, then the full series.
     *
     * @param  array<string, mixed>  $profile
     */
    public function narrow(Collection $batteries, array &$profile): Collection
    {
        $recommendation = $profile['recommendation'];

        $matchesTech = function (Battery $battery) use ($recommendation): bool {
            $model = strtoupper((string) $battery->model);
            $technology = strtoupper((string) $battery->technology);

            return match ($recommendation['technology']) {
                'agm' => str_contains($model, 'AGM'),
                'heavy' => ! str_contains($model, 'AGM')
                    && ! str_contains($model, 'EFB')
                    && ! str_contains($technology, 'MARINE'),
                default => true,
            };
        };

        $matchesCapacity = fn (Battery $battery): bool => $battery->capacity_ah !== null
            && $battery->capacity_ah >= $recommendation['capacity_min']
            && $battery->capacity_ah <= $recommendation['capacity_max'];

        $capacityLabel = $recommendation['capacity_label'];

        $exact = $batteries->filter(fn (Battery $battery) => $matchesTech($battery) && $matchesCapacity($battery));

        if ($exact->isNotEmpty()) {
            $profile['match_level'] = 'exact';
            $profile['match_message'] = 'Përshtatje e plotë: '.$exact->count().' modele plotësojnë teknologjinë e rekomanduar dhe kapacitetin '.$capacityLabel.'.';
            $profile['result_count'] = $exact->count();

            return $exact->values();
        }

        $byTechnology = $recommendation['technology'] !== null
            ? $batteries->filter($matchesTech)
            : collect();

        if ($byTechnology->isNotEmpty()) {
            $profile['match_level'] = 'technology';
            $profile['match_message'] = 'Gjetëm '.$byTechnology->count().' modele me '.$recommendation['label'].', por jo brenda kapacitetit '.$capacityLabel.' — kontrolloni specifikimet ose jepni kapacitetin e saktë.';
            $profile['result_count'] = $byTechnology->count();

            return $byTechnology->values();
        }

        $byCapacity = $batteries->filter($matchesCapacity);

        if ($byCapacity->isNotEmpty()) {
            $profile['match_level'] = 'capacity';
            $profile['match_message'] = 'Gjetëm '.$byCapacity->count().' modele me kapacitet brenda '.$capacityLabel.'; teknologjia e rekomanduar nuk u gjet në serinë e starterëve.';
            $profile['result_count'] = $byCapacity->count();

            return $byCapacity->values();
        }

        $profile['match_level'] = 'series';
        $profile['match_message'] = 'Nuk u gjet përputhje e saktë — po shfaqim të gjithë serinë e starterëve '.self::STARTER_CATEGORY.'. Na dërgoni VIN-in në WhatsApp për përshtatje të garantuar.';
        $profile['result_count'] = $batteries->count();

        return $batteries->values();
    }

    /**
     * Decode a VIN without a manufacturer table: validity, region from the
     * first character and candidate model years from the 10th character.
     *
     * @return array<string, mixed>
     */
    public function decodeVin(string $vin, ?int $selectedYear = null): array
    {
        $normalized = strtoupper((string) preg_replace('/[\s\-]/', '', trim($vin)));

        if ($normalized === '') {
            return ['state' => 'empty'];
        }

        if (strlen($normalized) !== 17) {
            return [
                'state' => 'invalid',
                'message' => 'VIN-i duhet të ketë saktësisht 17 karaktere.',
            ];
        }

        if (preg_match('/[IOQ]/', $normalized)) {
            return [
                'state' => 'invalid',
                'message' => 'VIN-i nuk mund të përmbajë karakteret I, O dhe Q.',
            ];
        }

        $yearCandidates = $this->vinModelYearCandidates($normalized[9]);
        $primaryYear = $selectedYear !== null && $yearCandidates !== []
            ? $this->nearestYear($yearCandidates, $selectedYear)
            : ($yearCandidates[0] ?? null);

        return [
            'state' => 'valid',
            'vin' => $normalized,
            'region' => $this->vinRegion($normalized[0]),
            'year_candidates' => $yearCandidates,
            'year' => $primaryYear,
            'year_matches_selection' => $primaryYear !== null
                && $selectedYear !== null
                && $primaryYear === $selectedYear,
        ];
    }

    private function vinRegion(string $character): string
    {
        return match (true) {
            $character >= '1' && $character <= '5' => 'SH.B.A. / Kanada',
            $character === '6' || $character === '7' => 'Australia / Zelanda e Re',
            $character === '8' || $character === '9' => 'Amerika e Jugut',
            $character >= 'A' && $character <= 'H' => 'Afrikë',
            $character === 'J' => 'Japoni',
            $character === 'K' => 'Korea e Jugut',
            $character === 'L' => 'Kinë',
            in_array($character, ['S', 'V', 'W', 'X', 'Y', 'Z'], true) => 'Evropë',
            default => 'E paidentifikuar',
        };
    }

    /**
     * Model-year code (10th VIN position): digits map to 2001–2009, letters
     * repeat on a 30-year cycle (1980–2000 and 2010–2030).
     *
     * @return list<int>
     */
    private function vinModelYearCandidates(string $code): array
    {
        $code = strtoupper($code);

        if (ctype_digit($code)) {
            return $code === '0' ? [] : [2000 + (int) $code];
        }

        $sequence = 'ABCDEFGHJKLMNPRSTVWXY';
        $position = strpos($sequence, $code);

        if ($position === false) {
            return [];
        }

        return [1980 + $position, 2010 + $position];
    }

    /**
     * @param  list<int>  $candidates
     */
    private function nearestYear(array $candidates, int $selectedYear): int
    {
        $nearest = $candidates[0];

        foreach ($candidates as $candidate) {
            if (abs($candidate - $selectedYear) < abs($nearest - $selectedYear)) {
                $nearest = $candidate;
            }
        }

        return $nearest;
    }
}
