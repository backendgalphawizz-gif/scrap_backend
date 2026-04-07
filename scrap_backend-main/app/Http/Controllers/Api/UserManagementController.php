<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\ImageUploadService;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    //
    public function index()
    {
        $users = User::with('role:id,name')
            ->whereHas('role', function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->latest()
            ->get(['id','name','mobile','email','image','role_id','internal_id','gmr_ci_id','gmr_mi_id','circle_assignment','status']);
        if(count($users)==0){
              return response()->json([
                'status' => false,
                'message'=> "No Record Found",
                'data' => $users
            ]);
        }

        return response()->json([
            'status' => true,
            'message'=> "Record Found",
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email'=> 'nullable|string|unique:users,email',
            'password' => 'nullable|min:6',
            'role_id' => 'required|exists:roles,id',
            'mobile' => 'nullable|digits_between:10,13|unique:users,mobile',
            'internal_id' => 'nullable|string',
            'gmr_ci_id' => 'nullable|string',
            'gmr_mi_id' => 'nullable|string',
            'circle_assignment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email??null,
            'password' => $request->password?Hash::make($request->password):null,
            'actual_password'=> $request->password??null,
            'role_id' => $request->role_id,
            'mobile' => $request->mobile,
            'internal_id' => $request->internal_id,
            'gmr_ci_id' => $request->gmr_ci_id,
            'gmr_mi_id' => $request->gmr_mi_id,
            'circle_assignment' => $request->circle_assignment,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            // 'data' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::with('role:id,name')->find($id,['id','name','email','mobile','role_id','image','status']);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id, ImageUploadService $imageService)
    {
       
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'   => 'sometimes|required|string|max:255',
            'email' => 'sometimes|string',
            'passwaord' => 'sometimes|string',
            'mobile' => [
                'sometimes',
                'required',
                Rule::unique('users', 'mobile')->ignore($id)
            ],
            'image'  => 'sometimes|image|max:2048',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $check = User::where('email',$request->email)->where('id','!=',$id)->first();
        if($check){
            return response()->json([
                    'status' => false,
                    'message' => 'Email already exist'
                ], 422);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->actual_password = $request->password;
        }

        if ($request->filled('mobile')) {
            $user->mobile = $request->mobile;
        }

        if ($request->hasFile('image')) {

            $imageUrl = $imageService->uploadToImgBB($request->file('image'));

            if (!$imageUrl) {
                return response()->json([
                    'status' => false,
                    'message' => 'Image upload failed'
                ], 500);
            }

            $user->image = $imageUrl;
        }
        

        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Profile updated successfully',
            'data'    => [
                'id'     => $user->id,
                'name'   => $user->name,
                'mobile' => $user->mobile,
                'image'  => $user->image,
            ]
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'status' => ['required', Rule::in([1, 2])]
        ]);

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User status updated successfully',
            'data' => [
                'id' => $user->id,
                'status' => $user->status
            ]
        ], 200);
    }
}
