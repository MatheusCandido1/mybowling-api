<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\Auth\LoggedUserResource;

class NotificationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function toggleRead(Notification $notification) {
        try {
        $notification->read_at = $notification->read_at ? null : now();
        $notification->save();

        return response()->json([
            'data' => $notification,
            'user' => new LoggedUserResource(auth()->user()),
        ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index() {

        $notifications = DB::table('notifications as n')
        ->select('n.id as id', 'n.user_id as user_id', 'n.read_at', 'epn.notification', 'n.type', 'n.author', 'n.support_id', 'n.created_at')
        ->join('expo_push_notifications as epn', 'n.expo_push_notifications_id', '=', 'epn.id')
        ->where('notifiable_id', auth()->user()->id)
        ->orderBy('n.created_at', 'desc')
        ->get();

        foreach ($notifications as $notification) {
            $notification->notification = json_decode($notification->notification, true);

            $notification->content = $notification->notification;

            unset($notification->notification);
            unset($notification->content['to']);
            unset($notification->content['priority']);

            $body = $notification->content['body'];
            $title = $notification->content['title'];
        }

        return response()->json([
            'data' => $notifications
        ], 200);

    }
}
