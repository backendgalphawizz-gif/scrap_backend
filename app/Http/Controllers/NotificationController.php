<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Seller;
use App\Model\DeliveryMan;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
// use App\Traits\CommonTrait;
class NotificationController extends Controller
{
    // use CommonTrait;

    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        $sevenDaysAgo = date('Y-m-d', strtotime('-7 days'));

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $notifications = Notification::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            })->whereDate('created_at', '>=', $sevenDaysAgo);
            $query_param = ['search' => $request['search']];
        } else {
            $notifications = Notification::whereDate('created_at', '>=', $sevenDaysAgo);
        }

        $notifications = $notifications->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.notification.index', compact('notifications','search'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'description' => 'required'
        // ], [
        //     'title.required' => 'title is required!',
        // ]);

        $notification = new Notification;
        $notification->title = $request->title ?? '';
        $notification->user_type = $request->user_type;

        
        $notification->description = $request->description ?? '';

        if ($request->has('image')) {
            $notification->image = ImageManager::upload('notification/', 'png', $request->file('image'));
        } else {
            $notification->image = 'null';
        }

        $notification->status             = 1;
        $notification->notification_count = 1;
        $notification->save();

        try {

            $tokens = [];
            if($request->user_type == 'user') {
                $tokens = User::where('fcm_id', '!=', '')->get()->pluck('fcm_id')->toArray();
            } else if($request->user_type == 'sale') {
                $tokens = Seller::where('cm_firebase_token', '!=', '')->get()->pluck('cm_firebase_token')->toArray();
            } else if($request->user_type == 'brand') {
                $tokens = DeliveryMan::where('fcm_token', '!=', '')->get()->pluck('fcm_token')->toArray();
                $ids = DeliveryMan::where('fcm_token', '!=', '')->get()->pluck('id')->toArray();

                if(!empty($ids)) {
                    foreach($ids as $driver_id) {
                        $data = [
                            'title' => $request->title,
                            'description' => $request->description,
                            'order_id' => '',
                            'image' => '',
                        ];
        
                        self::add_deliveryman_push_notification($data, $driver_id);
                    }
                }

            }

            $desktop_tokens = [];
            if($request->user_type == 'Resturants') {
                $desktop_tokens = Seller::where('desktop_firebase_token', '!=', '')->get()->pluck('desktop_firebase_token')->toArray();
            } else if($request->user_type == 'Customers') {
                $desktop_tokens = User::where('desktop_token', '!=', '')->get()->pluck('desktop_token')->toArray();
            }

            if(!empty($desktop_tokens)) {
                $tokens = array_merge($tokens, $desktop_tokens);
            }

            

            Helpers::send_push_notif_to_topic($notification, $tokens);
            //Toastr::success('Notification sent successfully!');
        } catch (\Exception $e) {
            //Toastr::warning('Push notification failed!');
        }

        return back();
    }

    public function edit($id)
    {
        $notification = Notification::find($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $request->has('image')? ImageManager::update('notification/', $notification->image, 'png', $request->file('image')):$notification->image;
        $notification->save();

        //Toastr::success('Notification updated successfully!');
        return redirect('/admin/notification/add-new');
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $notification = Notification::find($request->id);
            $notification->status = $request->status;
            $notification->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function resendNotification(Request $request){
        $notification = Notification::find($request->id);

        $data = array();
        try {

            $tokens = [];
            if($notification->user_type == 'Customers') {
                $tokens = User::where('cm_firebase_token', '!=', '')->get()->pluck('cm_firebase_token')->toArray();
            } else if($notification->user_type == 'Restaurants') {
                $tokens = Seller::where('cm_firebase_token', '!=', '')->get()->pluck('cm_firebase_token')->toArray();
            } else if($notification->user_type == 'Riders') {
                $tokens = DeliveryMan::where('fcm_token', '!=', '')->get()->pluck('fcm_token')->toArray();
            }
            Helpers::send_push_notif_to_topic($notification, $tokens);
            $notification->notification_count += 1;
            $notification->save();

            $data['success'] = true;
            $data['message'] = \App\CPU\translate("Push notification successfully!");
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage(); // \App\CPU\translate("Push notification failed!");
        }

        return $data;
    }

    public function delete(Request $request)
    {
        $notification = Notification::find($request->id);
        ImageManager::delete('/notification/' . $notification['image']);
        $notification->delete();
        return response()->json();
    }
}
