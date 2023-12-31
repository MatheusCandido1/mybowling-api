<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Game;

use App\Http\Requests\Group\GroupStoreRequest;

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
            $group->cover = $request->cover ?? null;
            $group->owner_id = auth()->user()->id;
            $group->limit_date = $request->limit_date ?? null;
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

    public function index() {
        try {
            $groups = auth()->user()->groups()->get();
            // Get a list of every member of each group (only avatar and name)

            $groups = $groups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'description' => $group->description,
                    'cover' => $group->cover,
                    'owner' => [
                        'id' => $group->owner->id,
                        'name' => $group->owner->name,
                        'avatar' => $group->owner->avatar,
                    ],
                    'owner_email' => $group->owner->email,
                    'limit_date' => $group->limit_date,
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

            $admin = Auth::user();

            if($admin->id !== $group->owner_id) {
                return response()->json([
                    'message' => 'Only the group owner can remove members'
                ], 403);
            }

            if($user->id === $group->owner_id) {
                return response()->json([
                    'message' => 'The group owner cannot be removed'
                ], 403);
            }

            // Remove user from group
            $group->members()->detach($user->id);

            DB::commit();

            return response()->json([
                'message' => 'User removed from group'
            ], 200);

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

            $ball_id = $request->query('ball');
            $location_id = $request->query('location');
            $start_date = $request->query('start_date');
            $end_date = $request->query('end_date');

            $games = Game::with('location', 'ball', 'user')
            ->ofBall($ball_id)
            ->ofLocation($location_id)
            ->ofStartDate($start_date)
            ->ofEndDate($end_date)
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
