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
        return view('admin-views.user-level.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'range_min' => 'required|integer|min:0',
            'range_max' => 'required|integer|min:0|gte:range_min',
            'max_participations_per_day' => 'required|integer|min:1',
        ]);
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
            'name' => 'required|string|max:255',
            'range_min' => 'required|integer|min:0',
            'range_max' => 'required|integer|min:0|gte:range_min',
            'max_participations_per_day' => 'required|integer|min:1',
        ]);
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
