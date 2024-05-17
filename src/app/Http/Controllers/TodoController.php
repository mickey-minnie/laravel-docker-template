<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todo = new Todo();
        $todoList = $todo->all();
        // $todoList は Collectionクラス

        return view('todo.index', ['todoList' => $todoList]);
    }

    public function create()
    {
        return view('todo.create'); 
    }

    public function store(Request $request)
    {
        $inputs = $request->all(); // 入力値を連想配列で取得

        $todo = new Todo(); 
        $todo->fill($inputs);
        $todo->save();

        return redirect()->route('todo.index');
    }
}