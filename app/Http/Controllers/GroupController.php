<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Game;

use App\Notifications\GroupInviteNotification;
use App\Models\Notification as NotificationModel;

use App\Http\Requests\Group\GroupStoreRequest;
use App\Http\Requests\Group\GroupUpdateRequest;

use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Game\GameResource;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function store(GroupStoreRequest $request) {
        try {
            DB::beginTransaction();

            $group = new Group();
            $group->name = $request->name;
            $group->description = $request->description ?? null;
            $group->owner_id = auth()->user()->id;
            $group->save();

            $group->members()->attach(auth()->user()->id, [
                'is_active' => true,
                'joined_at' => date('Y-m-d H:i:s')
            ]);


            DB::commit();

            return response()->json([
                'data' => $group,
                'message' => 'Group created'
            ], 201);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function invite(Request $request) {
        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();
            if(!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $group = Group::find($request->group_id);

            if(!$group) {
                return response()->json([
                    'message' => 'Group not found'
                ], 404);
            }

            // Check if user is already a member
            $isMember = $group->members()->where('user_id', $user->id)->first();

            if($isMember) {
                $isMember->pivot->touch();

                if($isMember->pivot->is_active) {
                    return response()->json([
                        'message' => 'User is already a member'
                    ], 400);
                } else {
                    return response()->json([
                        'message' => 'Invite already sent'
                    ], 400);
                }
            }

            $group->members()->attach($user->id, [
                'is_active' => false,
                'joined_at' => null
            ]);


            $user->notify(new GroupInviteNotification($group->name, $group->owner->name));

            $latestNotification = DB::table('expo_push_notifications')
            ->select('id', 'notification','notifiable_id','error', 'status')
            ->where('notifiable_id', $user->id)
            ->latest()
            ->first();

            NotificationModel::create([
                'author' => $group->owner->name,
                'type' => 'GROUP_INVITE',
                'support_id' => null,
                'user_id' => $user->id,
                'expo_push_notifications_id' => $latestNotification->id,
                'read_at' => null
            ]);

            DB::commit();


            return response()->json([
                'message' => 'Invite sent'
            ], 201);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Group $group) {
        try {
            DB::beginTransaction();

            $admin = Auth::user();

            if($admin->id !== $group->owner_id) {
                return response()->json([
                    'message' => 'Only the group owner can delete the group'
                ], 403);
            }

            $group->delete();

            DB::commit();

            return response()->json([
                'message' => 'Group deleted'
            ], 200);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(GroupUpdateRequest $request, Group $group) {
        try {
            DB::beginTransaction();

            $group = Group::find($group->id);

            if(!$group) {
                return response()->json([
                    'message' => 'Group not found'
                ], 404);
            }

            $group->name = $request->name;
            $group->description = $request->description ?? null;
            $group->save();

            DB::commit();

            return response()->json([
                'message' => 'Group updated'
            ], 200);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index() {
        try {
            $groups = auth()->user()->groups()->get();

            $groups = $groups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'description' => $group->description,
                    'owner' => [
                        'id' => $group->owner->id,
                        'name' => $group->owner->name,
                        'avatar' => $group->owner->avatar,
                    ],
                    'owner_email' => $group->owner->email,
                    'is_active' => $group->members()->where('user_id', auth()->user()->id)->first()->pivot->is_active,
                    'members' => $group->members->map(function ($member) {
                        return [
                            'id' => $member->id,
                            'name' => $member->name,
                            'avatar' => $member->avatar,
                        ];
                    }),
                ];
            });


            return GroupResource::collection($groups);


        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeUser(Group $group, User $user) {
        try {
            DB::beginTransaction();

            $loggedUser = auth()->user();

            if($loggedUser->id === $user->id || $loggedUser->id === $group->owner_id) {
                $group->members()->detach($user->id);

                DB::commit();

                return response()->json([
                    'message' => 'User removed from group'
                ], 200);
            }


            if($user->id === $group->owner_id) {
                return response()->json([
                    'message' => 'The group owner cannot be removed'
                ], 403);
            }


            if($loggedUser->id !== $group->owner_id) {
                return response()->json([
                    'message' => 'Only the group owner can remove members'
                ], 403);
            }
        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Group $group) {
        try {

            $details = [];

            $details['group'] = $group;

            $members = $group->members()->get();

            $details['members'] = $members->map(function ($member) use ($group) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'avatar' => $member->avatar,
                    'role' => $member->id === $group->owner_id ? 'Admin' : 'Member',
                    'is_active' => $member->pivot->is_active,
                    'joined_at' => $member->pivot->joined_at,
                ];
            });

            // Find the admin in the collection
            $admin = $details['members']->where('role', 'Admin')->first();

            // Remove the admin from the collection
            $details['members'] = $details['members']->reject(function ($member) {
                return $member['role'] === 'Admin';
            });

            // Prepend the admin to the beginning of the collection
            $details['members'] = $details['members']->prepend($admin);

            $details['locations'] = $group->games()
            ->selectRaw('locations.id as id, locations.name as name')
            ->join('locations', 'games.location_id', '=', 'locations.id')
            ->groupBy('id', 'name')
            ->get();

            $details['standings'] = $group->games()
            ->selectRaw('users.id as user_id, users.name as user_name, COUNT(*) AS total_games, AVG(total_score) AS average_score')
            ->join('users', 'games.user_id', '=', 'users.id')
            ->groupBy('user_id', 'user_name')
            ->orderBy('average_score', 'DESC')
            ->get()
            ->map(function ($item, $key) {
                return [
                    'position' => $key + 1,
                    'player' => $item->user_name,
                    'games' => $item->total_games,
                    'avg' => ceil($item->average_score),
                ];
            });



            return response()->json([
                'data' => $details
            ], 200);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function games(Group $group, Request $request) {
        try {

            $location_id = $request->query('location');
            $start_date = $request->query('start_date');
            $end_date = $request->query('end_date');
            $user_id = $request->query('user');

            $games = Game::with('location', 'ball', 'user')
            ->ofLocation($location_id)
            ->ofStartDate($start_date)
            ->ofEndDate($end_date)
            ->ofUser($user_id)
            ->where('status', 'COMPLETED')
            ->where('group_id', $group->id)
            ->orderBy('game_date', 'desc')
            ->paginate(4);

            return GameResource::collection($games);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function reply(Group $group, Request $request) {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Check if invite exists or if invite has already been accepted
            $isInvited = $group->members()->where('user_id', $user->id)->first();



            if (!$isInvited || $isInvited->is_active) {
                return response()->json([
                    'message' => $isInvited ? 'Invite already accepted' : 'Invite not found'
                ], 404);
            }

            if($request->reply === 'decline') {
                $group->members()->detach($user->id);
                DB::commit();
                return response()->json([
                    'message' => 'Invite declined'
                ], 200);
            } else {
                $group->members()->updateExistingPivot($user->id, [
                    'is_active' => true,
                    'joined_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Invite accepted'
            ], 201);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
