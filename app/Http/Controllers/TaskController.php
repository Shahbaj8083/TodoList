<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tasks,name',
        ]);

        $task = new Task();
        $task->name = $request->name;
        $task->save();

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $task->completed = $request->completed;
        $task->save();

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
