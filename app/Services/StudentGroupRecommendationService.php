<?php

namespace App\Services;

use Carbon\CarbonImmutable;

class StudentGroupRecommendationService
{
    /**
     * Calculate age at reference date (1 July of year 1 of the academic year) and recommend a group.
     *
     * @param  string  $tahunAjaran  YYYY/YYYY (e.g. 2026/2027)
     * @return array{
     *     tanggal_acuan: string,
     *     usia_bulan: int,
     *     usia_manusia: string,
     *     kelompok_rekomendasi: string
     * }
     */
    public function calculate(string|\DateTimeInterface $birthDate, string $tahunAjaran): array
    {
        // 1. Parse birthDate
        $birth = CarbonImmutable::parse($birthDate)->startOfDay();

        // 2. Extract start year from YYYY/YYYY (e.g. 2026/2027 -> 2026)
        $parts = explode('/', $tahunAjaran);
        $startYear = (int) $parts[0];

        // 3. Reference date: 1 July of the start year
        $referenceDate = CarbonImmutable::create($startYear, 7, 1)->startOfDay();

        // If birth date is after the reference date, age is 0 months
        if ($birth->greaterThan($referenceDate)) {
            $months = 0;
        } else {
            // 4. Calculate completed months.
            $months = $birth->diffInMonths($referenceDate);
        }

        // 5. Build age string in Indonesian "X tahun Y bulan"
        $years = (int) floor($months / 12);
        $remainingMonths = (int) ($months % 12);
        $ageString = "{$years} tahun {$remainingMonths} bulan";

        // 6. Recommendation
        if ($months >= 48 && $months <= 59) {
            $recommendation = 'A';
        } elseif ($months >= 60 && $months <= 83) {
            $recommendation = 'B';
        } else {
            $recommendation = 'perlu_konfirmasi';
        }

        return [
            'tanggal_acuan' => $referenceDate->format('Y-m-d'),
            'usia_bulan' => $months,
            'usia_manusia' => $ageString,
            'kelompok_rekomendasi' => $recommendation,
        ];
    }
}
