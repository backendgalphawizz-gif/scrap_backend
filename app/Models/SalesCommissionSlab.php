<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesCommissionSlab extends Model
{
    protected $fillable = [
        'min_earning',
        'max_earning',
        'rate',
    ];

    protected $casts = [
        'min_earning' => 'float',
        'max_earning' => 'float',
        'rate'        => 'float',
    ];

    /**
     * Return all slabs ordered by min_earning ascending (always the canonical order).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function ordered(): \Illuminate\Database\Eloquent\Collection
    {
        return static::orderBy('min_earning')->get();
    }

    /**
     * Validate a proposed set of slabs for gaps and overlaps.
     *
     * @param  array<array{min_earning: float|string, max_earning: float|string|null, rate: float|string}> $slabs
     * @return array{gaps: array<string>, overlaps: array<string>}
     */
    public static function validateSlabs(array $slabs): array
    {
        $gaps      = [];
        $overlaps  = [];

        // Sort by min_earning
        usort($slabs, fn ($a, $b) => (float) $a['min_earning'] <=> (float) $b['min_earning']);

        $count = count($slabs);
        for ($i = 0; $i < $count; $i++) {
            $min = (float) $slabs[$i]['min_earning'];
            $max = isset($slabs[$i]['max_earning']) && $slabs[$i]['max_earning'] !== '' && $slabs[$i]['max_earning'] !== null
                ? (float) $slabs[$i]['max_earning']
                : null;

            // Check for open-ended slab not being the last
            if ($max === null && $i < $count - 1) {
                $overlaps[] = 'Slab starting at ₹' . number_format($min, 2) . ' has no upper limit but is not the last slab.';
            }

            if ($i > 0) {
                $prevMax = isset($slabs[$i - 1]['max_earning']) && $slabs[$i - 1]['max_earning'] !== '' && $slabs[$i - 1]['max_earning'] !== null
                    ? (float) $slabs[$i - 1]['max_earning']
                    : null;

                if ($prevMax === null) {
                    // already flagged above
                } elseif ($prevMax > $min) {
                    $overlaps[] = 'Slabs starting at ₹' . number_format((float) $slabs[$i - 1]['min_earning'], 2)
                        . ' and ₹' . number_format($min, 2) . ' overlap.';
                } elseif ($prevMax < $min) {
                    $gaps[] = 'Gap between ₹' . number_format($prevMax, 2) . ' and ₹' . number_format($min, 2) . '.';
                }
            }
        }

        return compact('gaps', 'overlaps');
    }
}
