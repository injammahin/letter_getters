<?php

namespace App\Services;

use App\Models\ChildLetter;

class ChildLetterScanService
{
    public function scan(ChildLetter $letter): array
    {
        $terms = collect(config('child_letter_scan.terms', []))
            ->map(fn ($term) => trim(mb_strtolower($term)))
            ->filter()
            ->unique()
            ->values();

        $content = mb_strtolower(trim(($letter->subject ?? '') . ' ' . ($letter->body ?? '')));

        $hits = [];

        foreach ($terms as $term) {
            $pattern = '/(?<![\p{L}\p{N}])' . preg_quote($term, '/') . '(?![\p{L}\p{N}])/iu';

            preg_match_all($pattern, $content, $matches);

            $count = count($matches[0] ?? []);

            if ($count > 0) {
                $hits[] = [
                    'term' => $term,
                    'count' => $count,
                ];
            }
        }

        $distinctHitCount = count($hits);
        $totalHitCount = collect($hits)->sum('count');

        $flagged = $distinctHitCount >= (int) config('child_letter_scan.minimum_distinct_terms_to_flag', 1);

        return [
            'scan_status' => 'scanned',
            'scan_flagged' => $flagged,
            'scan_hits' => $hits,
            'scan_summary' => $flagged
                ? "Red flag: suspicious words found. Distinct matches: {$distinctHitCount}, total matches: {$totalHitCount}."
                : 'Scan complete. No suspicious words found.',
        ];
    }

    public function applyScan(ChildLetter $letter, ?int $adminId = null): ChildLetter
    {
        $result = $this->scan($letter);

        $letter->update([
            'scan_status' => $result['scan_status'],
            'scan_flagged' => $result['scan_flagged'],
            'scan_hits' => $result['scan_hits'],
            'scan_summary' => $result['scan_summary'],
            'scanned_by' => $adminId,
            'scanned_at' => now(),
        ]);

        return $letter->fresh();
    }
}