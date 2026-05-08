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

        $overlaps = UserLevel::where('range_min', '<=', $request->range_max)
            ->where('range_max', '>=', $request->range_min)
            ->exists();

        if ($overlaps) {
            return back()->withErrors(['range_min' => 'The specified range overlaps with an existing user level.'])->withInput();
        }

        UserLevel::create($request->all());
        return redirect()->route('admin.user-level.index')->with('success', 'User level created successfully.');
    }

    public function edit($id)
    {
        $level = UserLevel::findOrFail($id);
        return view('admin-views.user-level.edit', compact('level'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                       => 'required|string|max:255|unique:user_levels,name,' . $id,
            'range_min'                  => 'required|integer|min:0',
            'range_max'                  => 'required|integer|min:0|gte:range_min',
            'max_participations_per_day' => 'required|integer|min:1',
        ]);

        $overlaps = UserLevel::where('id', '!=', $id)
            ->where('range_min', '<=', $request->range_max)
            ->where('range_max', '>=', $request->range_min)
            ->exists();

        if ($overlaps) {
            return back()->withErrors(['range_min' => 'The specified range overlaps with an existing user level.'])->withInput();
        }

        $level = UserLevel::findOrFail($id);
        $level->update($request->all());
        return redirect()->route('admin.user-level.index')->with('success', 'User level updated successfully.');
    }

    public function destroy($id)
    {
        $level = UserLevel::findOrFail($id);
        $level->delete();
        return redirect()->route('admin.user-level.index')->with('success', 'User level deleted successfully.');
    }
}
