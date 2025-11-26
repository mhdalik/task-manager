<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required|between:2,255',
            'description' => 'string|max:999',
            'is_completed' => 'boolean',
            'due_date' => 'date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $task = Task::create($validator->validated());

        return response()->json([
            'status' => true,
            'data' => $task,
            'message' => 'Task created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task->toResource();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required|between:2,255',
            'description' => 'string|max:999',
            'is_completed' => 'boolean',
            'due_date' => 'date|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $status = $task->update($validator->validated());

        return response()->json([
            'status' => $status,
            'data' => $task->fresh(),
            'message' => 'Task updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([
            'status' => true,
            'data' => $task,
            'message' => 'Task deleted successfully'
        ]);
    }
}
