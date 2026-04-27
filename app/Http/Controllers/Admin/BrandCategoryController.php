<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandCategoryController extends Controller
{
    public function index(Request $request)
    {
        $brandCategories = BrandCategory::query()
            ->with('parent:id,name')
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->name) . '%');
            })
            ->when($request->filled('parent_id'), function ($query) use ($request) {
                if ((int) $request->parent_id === 0) {
                    $query->where('parent_id', 0);
                    return;
                }

                $query->where('parent_id', (int) $request->parent_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', (int) $request->status);
            })
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $parents = BrandCategory::query()
            ->where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin-views.brand-category.index', compact('brandCategories', 'parents'));
    }

    public function create()
    {
        $parents = BrandCategory::query()
            ->where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin-views.brand-category.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $parentId = (int) $request->input('parent_id', 0);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('brand_categories', 'name')->where(function ($query) use ($parentId) {
                    return $query->where('parent_id', $parentId);
                }),
            ],
            'parent_id' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        if ($parentId > 0 && !BrandCategory::query()->where('id', $parentId)->exists()) {
            return back()->withErrors(['parent_id' => 'Selected parent category is invalid.'])->withInput();
        }

        BrandCategory::create([
            'name' => trim($request->name),
            'parent_id' => $parentId,
            'status' => $request->boolean('status'),
        ]);

        return redirect()->route('admin.brand-category.index')->with('success', 'Brand category created successfully.');
    }

    public function edit($id)
    {
        $brandCategory = BrandCategory::findOrFail($id);
        $parents = BrandCategory::query()
            ->where('parent_id', 0)
            ->where('id', '!=', $brandCategory->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin-views.brand-category.edit', compact('brandCategory', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $brandCategory = BrandCategory::findOrFail($id);
        $parentId = (int) $request->input('parent_id', 0);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('brand_categories', 'name')
                    ->where(function ($query) use ($parentId) {
                        return $query->where('parent_id', $parentId);
                    })
                    ->ignore($brandCategory->id),
            ],
            'parent_id' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        if ($parentId > 0 && !BrandCategory::query()->where('id', $parentId)->exists()) {
            return back()->withErrors(['parent_id' => 'Selected parent category is invalid.'])->withInput();
        }

        if ($parentId === $brandCategory->id) {
            return back()->withErrors(['parent_id' => 'Parent category cannot be self.'])->withInput();
        }

        $brandCategory->name = trim($request->name);
        $brandCategory->parent_id = $parentId;
        $brandCategory->status = $request->boolean('status');
        $brandCategory->save();

        return redirect()->route('admin.brand-category.index')->with('success', 'Brand category updated successfully.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:brand_categories,id',
        ]);

        $brandCategory = BrandCategory::findOrFail($request->id);

        BrandCategory::query()
            ->where('parent_id', $brandCategory->id)
            ->delete();

        $brandCategory->delete();

        return redirect()->route('admin.brand-category.index')->with('success', 'Brand category deleted successfully.');
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:brand_categories,id',
            'status' => 'required|boolean',
        ]);

        $brandCategory = BrandCategory::findOrFail($request->id);
        $brandCategory->status = (bool) $request->status;
        $brandCategory->save();

        if (!$brandCategory->status) {
            BrandCategory::query()
                ->where('parent_id', $brandCategory->id)
                ->update(['status' => false]);
        }

        return redirect()->route('admin.brand-category.index')->with('success', 'Brand category status updated successfully.');
    }
}
