<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::with('assignedUsers')->where('user_id', Auth::id());

        if ($request->has('status')) $query->where('status', $request->input('status'));
        if ($request->has('priority')) $query->where('priority', $request->input('priority'));
        if ($request->has('due_date')) $query->whereDate('due_date', $request->input('due_date'));

        if ($request->has('sort')) {
            $field = ltrim($request->input('sort'), '-');
            $dir = str_starts_with($request->input('sort'), '-') ? 'DESC' : 'ASC';
            $query->orderBy($field, $dir);
        }

        $tasks = $query->paginate(10);
        return $this->sendSuccessJson($tasks, "All tasks.", 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = Auth::user()->tasks()->create($data);
        return $this->sendSuccessJson($task, "Task created.", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with('user')->find($id);
        if (!$task) {
            return $this->sendErrorJson('Task Not Found!');
        }
        if (Auth::id() != $task->user_id) {
            return $this->sendErrorJson('Forbidden Access', [], 403);
        }
        return $this->sendSuccessJson($task, "Task details.", 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->sendErrorJson('Task Not Found!');
        }
        if (Auth::id() != $task->user_id) {
            return $this->sendErrorJson('Forbidden Access', [], 403);
        }
        $task->update($request->validated());
        return $this->sendSuccessJson($task->fresh(), "Task updated.", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->sendErrorJson('Task Not Found!');
        }
        $task->delete();
        return $this->sendSuccessJson(null, "Task deleted.", 200);
    }

    /**
     * Assign a task to other users
     */
    public function assign(AssignTaskRequest $request, $id): JsonResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->sendErrorJson('Task Not Found!');
        }
        if (Auth::id() != $task->user_id) {
            return $this->sendErrorJson('Forbidden Access', [], 403);
        }
        $data = $request->validated();
        $task->assignedUsers()->syncWithoutDetaching([$data['user_id']]);
        return $this->sendSuccessJson(null, "User assigned successfully.", 200);
    }
}
