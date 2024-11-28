<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContributorController extends Controller
{
    public function modifyContributor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'string', 'in:admin,editor,viewer'],
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

        if ($task->author_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not allowed to add contributors to this task',
            ], 403);
        }

        $contributorUser = User::where('email', $request->email)->first(['id', 'name', 'email']);

        if ($request->action === 'add') {
            $task->contributors()->attach($contributorUser->id, ['role' => $request->role]);
        } else {
            $task->contributors()->detach($contributorUser->id);
        }

        return response()->json(['contributor' => $contributorUser]);
    }
}
