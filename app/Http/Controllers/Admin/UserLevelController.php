<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLevel;

class UserLevelController extends Controller
{
    public function index()
    {
        $levels = UserLevel::all();
        return view('admin-views.user-level.index', compact('levels'));
    }

    public function create()
    {
        $lastLevel = UserLevel::orderBy('range_max', 'desc')->first();
        $nextMin   = $lastLevel ? ($lastLevel->range_max + 1) : 0;
        $prevMax   = $lastLevel ? $lastLevel->max_participations_per_day : null;
        return view('admin-views.user-level.create', compact('nextMin', 'prevMax'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                       => 'required|string|max:255|unique:user_levels,name',
            'range_min'                  => 'required|integer|min:0',
            'range_max'                  => 'required|integer|min:0|gte:range_min',
            'max_participations_per_day' => 'required|integer|min:1',
        ]);

        $rangeMin = (int) $request->range_min;
        $expectedMin = $this->expectedRangeMin();

        if ($rangeMin !== $expectedMin) {
            $message = $expectedMin === 0
                ? 'The first level must start at 0 (e.g. 0 - 500).'
                : "Range must start at {$expectedMin} immediately after the previous level (e.g. {$expectedMin} - 1000, not " . ($expectedMin - 1) . " - 1000).";

            return back()->withErrors(['range_min' => $message])->withInput();
        }

        UserLevel::create($request->only([
            'name',
            'range_min',
            'range_max',
            'max_participations_per_day',
        ]));

        return redirect()->route('admin.user-level.index')->with('success', 'User level created successfully.');
    }

    public function edit($id)
    {
        $level = UserLevel::findOrFail($id);
        ['previous' => $previous, 'next' => $next] = $this->getAdjacentLevels($level);
        $expectedMin = $previous ? (int) $previous->range_max + 1 : 0;
        $lockedMax   = $next ? (int) $next->range_min - 1 : null;

        return view('admin-views.user-level.edit', compact('level', 'previous', 'next', 'expectedMin', 'lockedMax'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                       => 'required|string|max:255|unique:user_levels,name,' . $id,
            'range_min'                  => 'required|integer|min:0',
            'range_max'                  => 'required|integer|min:0|gte:range_min',
            'max_participations_per_day' => 'required|integer|min:1',
        ]);

        $level = UserLevel::findOrFail($id);
        $rangeMin = (int) $request->range_min;
        $rangeMax = (int) $request->range_max;
        ['previous' => $previous, 'next' => $next] = $this->getAdjacentLevels($level);

        $expectedMin = $previous ? $previous->range_max + 1 : 0;

        if ($rangeMin !== $expectedMin) {
            $message = $expectedMin === 0
                ? 'The first level must start at 0.'
                : "Range must start at {$expectedMin} (previous level ends at {$previous->range_max}).";

            return back()->withErrors(['range_min' => $message])->withInput();
        }

        if ($next !== null && ($rangeMax + 1) !== (int) $next->range_min) {
            $requiredMax = $next->range_min - 1;

            return back()->withErrors([
                'range_max' => "Range max must be {$requiredMax} so the next level can start at {$next->range_min} (e.g. {$rangeMin} - {$requiredMax}, not {$rangeMin} - {$rangeMax}).",
            ])->withInput();
        }

        $level->update($request->only([
            'name',
            'range_min',
            'range_max',
            'max_participations_per_day',
        ]));

        return redirect()->route('admin.user-level.index')->with('success', 'User level updated successfully.');
    }

    public function destroy($id)
    {
        $level = UserLevel::findOrFail($id);
        $level->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'User level deleted successfully.']);
        }

        return redirect()->route('admin.user-level.index')->with('success', 'User level deleted successfully.');
    }

    /** Next valid range_min when appending a new level (0 if none exist). */
    private function expectedRangeMin(?int $excludeId = null): int
    {
        $query = UserLevel::query();

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        $last = $query->orderByDesc('range_max')->first();

        return $last ? (int) $last->range_max + 1 : 0;
    }

    /** @return array{previous: ?UserLevel, next: ?UserLevel} */
    private function getAdjacentLevels(UserLevel $level): array
    {
        $levels = UserLevel::orderBy('range_min')->get();
        $index = $levels->search(fn (UserLevel $l) => $l->id === $level->id);

        if ($index === false) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $index > 0 ? $levels[$index - 1] : null,
            'next'     => $index < $levels->count() - 1 ? $levels[$index + 1] : null,
        ];
    }
}
