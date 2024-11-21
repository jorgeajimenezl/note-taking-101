<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller 
{
    public function index() 
    {
        $tasks = Task::all()->sortBy('created_at');
        return view('task.index')->with('tasks', $tasks);
    }

    public function create() 
    {
        return view('task.create');
    }

    public function show($id) 
    {
        $task = Task::findOrFail($id);
        return view('task.show')->with('task', $task);
    }
}
