<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksConstroller extends Controller
{
    public function index() {
        return response()->json('task index method');
    }

}
