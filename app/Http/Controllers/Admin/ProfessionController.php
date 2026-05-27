<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfessionController extends Controller
{
    public function index()
    {
        $professions = Profession::orderBy('name')->get();

        return view('admin-views.profession.index', compact('professions'));
    }

    public function create()
    {
        return view('admin-views.profession.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:45|unique:professions,name',
            'status' => 'nullable|boolean',
        ]);

        Profession::create([
            'name' => trim($request->name),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('admin.profession.index')->with('success', 'Profession created successfully.');
    }

    public function edit($id)
    {
        $profession = Profession::findOrFail($id);

        return view('admin-views.profession.edit', compact('profession'));
    }

    public function update(Request $request, $id)
    {
        $profession = Profession::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('professions', 'name')->ignore($profession->id),
            ],
            'status' => 'nullable|boolean',
        ]);

        $profession->update([
            'name' => trim($request->name),
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('admin.profession.index')->with('success', 'Profession updated successfully.');
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:professions,id',
            'status' => 'required|boolean',
        ]);

        $profession = Profession::findOrFail($request->id);
        $profession->status = (bool) $request->status;
        $profession->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'Profession status updated successfully.']);
        }

        return redirect()->route('admin.profession.index')->with('success', 'Profession status updated successfully.');
    }

    public function destroy($id)
    {
        $profession = Profession::findOrFail($id);
        $profession->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'Profession deleted successfully.']);
        }

        return redirect()->route('admin.profession.index')->with('success', 'Profession deleted successfully.');
    }
}
