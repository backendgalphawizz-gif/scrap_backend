<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
// use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function add_new()
    {
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.add-new', compact('rls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:25|regex:/^[A-Za-z\s]+$/',
            'phone'    => 'required|digits:10',
            'role_id'  => 'required',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,bmp,tif,tiff|max:2048',
            'email'    => 'required|email|max:40|unique:admins,email',
            'password' => 'required|string|min:6|max:20|confirmed',
        ], [
            'name.required'             => 'Full name is required.',
            'name.regex'                => 'Name must contain only letters and spaces.',
            'phone.required'            => 'Phone number is required.',
            'phone.digits'              => 'Phone number must be exactly 10 digits.',
            'role_id.required'          => 'Please select a role.',
            'image.required'            => 'Admin image is required.',
            'image.image'               => 'Uploaded file must be an image.',
            'email.required'            => 'Email address is required.',
            'email.unique'              => 'This email is already registered.',
            'password.required'         => 'Password is required.',
            'password.min'              => 'Password must be at least 6 characters.',
            'password.confirmed'        => 'Password and confirm password do not match.',
        ]);

        if ($request->role_id == 1) {
            session()->flash('error', 'Access Denied! You cannot assign the Super Admin role.');
            return back();
        }

        DB::table('admins')->insert([
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'admin_role_id' => $request->role_id,
            'password'      => bcrypt($request->password),
            'status'        => 1,
            'image'         => ImageManager::upload('profile/', 'png', $request->file('image')),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        session()->flash('success', 'Admin added successfully!');
        return redirect()->route('admin.employee.list');
    }

    function list(Request $request)
    {
        $search = $request['search'];
        $key = explode(' ', $request['search']);
        $em = Admin::with(['role'])->whereNotIn('admin_role_id', [1,3])
                    ->when($search, function ($query) use ($key) {

                        $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('name', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%");
                            }
                        })

                        ->orWhereHas('role', function ($q) use ($key) {
                            $q->where(function ($r) use ($key) {
                                foreach ($key as $value) {
                                    $r->orWhere('name', 'like', "%{$value}%");
                                }
                            });
                        });

                    })
                    ->paginate(Helpers::pagination_limit());
        return view('admin-views.employee.list', compact('em','search'));
    }

    public function edit($id)
    {
        $e = Admin::where(['id' => $id])->first();
        $rls = AdminRole::whereNotIn('id', [1])->get();
        return view('admin-views.employee.edit', compact('rls', 'e'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:25|regex:/^[A-Za-z\s]+$/',
            'role_id'  => 'required',
            'password' => 'nullable|string|min:8|max:20',
        ], [
            'name.required' => 'Full name is required.',
            'name.regex'    => 'Name must contain only letters and spaces.',
            'role_id.required' => 'Please select a role.',
            'password.min'  => 'Password must be at least 8 characters.',
        ]);

        if ($request->role_id == 1) {
            session()->flash('error', 'Access Denied! You cannot assign the Super Admin role.');
            return back();
        }

        $e = Admin::find($id);
        $pass = ($request->filled('password')) ? bcrypt($request->password) : $e->getRawOriginal('password');

        $rawImage = $e->getRawOriginal('image'); // raw filename, bypass URL accessor
        $image = $rawImage;
        if ($request->hasFile('image')) {
            $image = ImageManager::update('profile/', $rawImage, 'png', $request->file('image'));
        }

        DB::table('admins')->where(['id' => $id])->update([
            'name'          => $request->name,
            'admin_role_id' => $request->role_id,
            'password'      => $pass,
            'image'         => $image,
            'updated_at'    => now(),
        ]);

        session()->flash('success', 'Admin updated successfully!');
        return redirect()->route('admin.employee.update', $id);
    }


    public function status(Request $request)
    {
        $employee = Admin::find($request->id);
    
        $employee->status = $request->status;
        $employee->save();
    
        // Toastr::success('Employee status updated!');
        return back();
    }

}
