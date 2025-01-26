<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContributorController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required_if:action,add', 'string', 'in:admin,editor,viewer'],
            'task' => ['required', 'integer', 'exists:tasks,id'],
            'action' => ['required', 'string', 'in:add,remove'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        $task = Task::find($request->task);

        if (auth()->id() === null || $task->author_id !== auth()->id()) {
            return response()->json([
                'errors' => ['You are not allowed to change contributors to this task'],
            ], 403);
        }

        $contributorUser = User::where('email', $request->email)->first(['id', 'name', 'email']);

        if ($contributorUser->id === auth()->id()) {
            return response()->json([
                'errors' => ['You cannot add yourself as a contributor'],
            ], 400);
        }

        if ($contributorUser === null) {
            return response()->json([
                'errors' => ['User not found'],
            ], 404);
        }

        if ($request->action === 'add') {
            $task->contributors()->create([
                'user_id' => $contributorUser->id,
                'role' => $request->role,
            ]);

            return response()->json(['message' => 'Contributor added', 'contributor' => $contributorUser]);
        } else {
            $task->contributors()->where('user_id', $contributorUser->id)->delete();

            return response()->json((['message' => 'Contributor removed']));
        }
    }
}
