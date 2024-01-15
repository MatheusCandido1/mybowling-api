<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Relative\LaravelExpoPushNotifications\PushNotification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index() {

        $notifications = DB::table('expo_push_notifications')
        ->select('id', 'notification','notifiable_id','error', 'status')
        ->where('notifiable_id', auth()->user()->id)
        ->get();

        foreach ($notifications as $notification) {
            $notification->notification = json_decode($notification->notification, true);
            $to = $notification->notification['to'];
            $body = $notification->notification['body'];
            $title = $notification->notification['title'];
            $priority = $notification->notification['priority'];
        }


        return response()->json([
            'data' => $notifications
        ], 200);

    }
}
