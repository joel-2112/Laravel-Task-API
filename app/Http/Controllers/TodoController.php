<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Http\Resources\TodoCollection;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    //display list of todos
    public function index()
    {
        $todos = Todo::all();
        return new TodoCollection($todos);
    }

    
     //Store a newly created todo in storage.
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'completed' => 'boolean',
                'user_id' => 'nullable|exists:users,id',
                'priority' => 'in:low,medium,high',
                'due_date' => 'nullable|date',
                'category' => 'nullable|string|max:100',
                'notes' => 'nullable|string',
            ]);

            $todo = Todo::create($validated);
            return (new TodoResource($todo))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create todo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //desplay specific todo by id    
    public function show(Todo $todo)
    {
        return new TodoResource($todo);
    }

    //update the todo by id
    public function update(Request $request, Todo $todo)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'completed' => 'sometimes|boolean',
                'user_id' => 'sometimes|nullable|exists:users,id',
                'priority' => 'sometimes|in:low,medium,high',
                'due_date' => 'nullable|date',
                'category' => 'nullable|string|max:100',
                'notes' => 'nullable|string',
            ]);

            $todo->update($validated);
            return new TodoResource($todo);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update todo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   //delete the todo
    public function destroy(Todo $todo)
    {
        try {
            $todo->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete todo',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}