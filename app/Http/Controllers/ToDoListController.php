<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToDoListController extends Controller
{
    public function index()
    {
        return view(sprintf('dashboard.%s.todo-list', Auth::user()->account));
    }
}
