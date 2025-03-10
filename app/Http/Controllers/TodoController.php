<?php

namespace App\Http\Controllers;
use App\Models\Todo;
class TodoController extends Controller
{
    //display list of todos
    public function getAllTodos()
    {
        $todos = Todo::all();
        return response()->json([
            'message' => 'Retrieved successfully',
            'todo' => $todos,
        ]);
    }
      
    //create a new todo
    public function createTodo(){
        $todo = new Todo();
        $todo->title = request('title');
        $todo->description = request('description');
        $todo->save();
        return response()->json([
            'message' => 'You have created todo successfully',
            'todo' => $todo,
        ]);

    }

    //display a specific todo

    public function getTodoById($id){
        $todo = Todo::find($id);
        if($todo){
            return response()->json([
                'message' => 'Retrieved successfully',
                'todo' => $todo,
            ]);
        }else{
            return response()->json([
                'message' => 'Todo not found',
            ], 404);
        }
    }
    //update a specific todo
    public function updateTodoById($id){
        $todo = Todo::find($id);
        if($todo){
            $todo->title = request('title');
            $todo->description = request('description');
            $todo->save();
            return response()->json(
                [
                'message' => 'Todo updated successfully',
                'todo' => $todo,
                ]
        );
        }else{
            return response()->json([
                'message' => 'Todo not found',
            ], 404);
        }
    }

    //delete a specific todo

    public function deleteTodoById($id){
        $todo = Todo::find($id);
        if($todo){
            $todo->delete();
            return response()->json([
                'message' => 'the task deleted successfully',
            ]);
        }else{
            return response()->json([
                'message' => 'Todo not found',
            ], 404);
        }
    }





}