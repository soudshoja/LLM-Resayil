<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the user's team members.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $teamMembers = TeamMember::forOwner($user->id)
            ->with(['member', 'addedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Transform team members to include member details and role
        $teamMembers->getCollection()->transform(function ($teamMember) {
            return [
                'id' => $teamMember->id,
                'member' => [
                    'id' => $teamMember->member->id,
                    'name' => $teamMember->member->name,
                    'email' => $teamMember->member->email,
                    'phone' => $teamMember->member->phone,
                ],
                'role' => $teamMember->role,
                'is_admin' => $teamMember->isAdmin(),
                'is_member' => $teamMember->isMember(),
                'added_by' => $teamMember->addedBy ? [
                    'id' => $teamMember->addedBy->id,
                    'name' => $teamMember->addedBy->name,
                    'email' => $teamMember->addedBy->email,
                ] : null,
                'joined_at' => $teamMember->joined_at?->toISOString(),
                'created_at' => $teamMember->created_at->toISOString(),
                'updated_at' => $teamMember->updated_at->toISOString(),
            ];
        });

        return response()->json([
            'data' => $teamMembers,
        ]);
    }

    /**
     * Store a newly created team member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'member_email' => 'required|string|email|max:255',
            'role' => 'required|in:admin,member',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Find user by email or phone
        $member = User::where('email', $request->member_email)
            ->orWhere('phone', $request->member_email)
            ->first();

        if (!$member) {
            return response()->json([
                'message' => 'User not found with the provided email or phone.',
            ], 404);
        }

        // Check if user is already on the team
        $existing = TeamMember::where('team_owner_id', $user->id)
            ->where('member_user_id', $member->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'User is already a member of this team.',
            ], 409);
        }

        // Check if user is trying to add themselves
        if ($member->id === $user->id) {
            return response()->json([
                'message' => 'You cannot add yourself to your own team.',
            ], 400);
        }

        // Create the team member record
        $teamMember = TeamMember::create([
            'team_owner_id' => $user->id,
            'member_user_id' => $member->id,
            'role' => $request->role,
            'added_by_id' => $user->id,
            'joined_at' => now(),
        ]);

        return response()->json([
            'message' => 'Team member added successfully.',
            'data' => [
                'id' => $teamMember->id,
                'member' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                ],
                'role' => $teamMember->role,
                'is_admin' => $teamMember->isAdmin(),
                'is_member' => $teamMember->isMember(),
            ],
        ], 201);
    }

    /**
     * Remove the specified team member from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $teamMember = TeamMember::where('id', $id)
            ->where('team_owner_id', $user->id)
            ->first();

        if (!$teamMember) {
            return response()->json([
                'message' => 'Team member not found or unauthorized.',
            ], 404);
        }

        $teamMember->delete();

        return response()->json([
            'message' => 'Team member removed successfully.',
        ]);
    }
}
