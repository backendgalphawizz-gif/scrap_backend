<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    //
     public function index()
    {
        $roles = Role::latest()->get(['id','name']);

        return response()->json([
            'status' => true,
            'message' => 'Role list fetched successfully',
            'data' => $roles
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $check = Role::where('name', $request->name)->first();

        if ($check) {
            return response()->json([
                'status' => false,
                'message' => 'Role already exists',
            ], 409); // 409 Conflict (better status code)
        }

        Role::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Role created successfully',
        ], 201);
    }

    public function show($id)
    {
        $role = Role::find($id, ['id', 'name']);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Role details fetched successfully',
            'data' => $role
        ], 200);
    }

     public function update(Request $request, $id)
    {
        $role = Role::find($id,['id','name']);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (Role::where('name', $request->name)
                ->where('id', '!=', $id)
                ->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Role name already taken',
            ], 409);
        }

        $role->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully',
            'data' => $role
        ], 200);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        // 1️⃣ Check role exists
        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ], 404);
        }

        // 2️⃣ ✅ ADD CHECK HERE (Before delete)
        if ($role->users()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Role is assigned to users. Cannot delete.'
            ], 400);
        }

        // 3️⃣ Delete role
        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully'
        ], 200);
    }

}
