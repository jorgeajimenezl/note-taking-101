<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function modifyContributor(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'role' => ['required', 'in:admin,editor,viewer'],
            'task' => ['id', 'exists:tasks,id'],
            'action' => ['required', 'in:add,remove'],
        ]);

        dump($validatedData);

        $task = Task::find($request->task);

        if ($task->author_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not allowed to add contributors to this task',
            ], 403);
        }

        $contributorUser = User::where('email', $request->email)->first();

        if ($request->action === 'action') {
            $task->contributors()->attach($contributorUser->id, ['role' => $request->role]);
        } else {
            $task->contributors()->detach($contributorUser->id);
        }

        return response()->json(null, 200);
    }
}
