<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Notification;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\Auth\LoggedUserResource;

use App\Notifications\GeneralMessageNotification;
use App\Models\Notification as NotificationModel;

class NotificationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function store(Request $request) {
        try {

            $user = User::find(1);

            $user->notify(new GeneralMessageNotification($request->title, $request->message));

            $latestNotification = DB::table('expo_push_notifications')
            ->select('id', 'notification','notifiable_id','error', 'status')
            ->where('notifiable_id', $user->id)
            ->latest()
            ->first();

            NotificationModel::create([
                'author' => '"SplitMate Team"',
                'type' => 'GENERAL_MESSAGE',
                'user_id' => $user->id,
                'expo_push_notifications_id' => $latestNotification->id,
                'read_at' => null
            ]);

            $latestNotification = DB::table('expo_push_notifications')
            ->select('id', 'notification','notifiable_id','error', 'status')
            ->where('notifiable_id', $user->id)
            ->latest()
            ->first();

            return response()->json([
                'data' => 'Notification sent',
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
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
        ->select('n.id as id', 'n.user_id as user_id', 'n.read_at', 'epn.notification', 'n.type', 'n.author', 'n.created_at')
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
