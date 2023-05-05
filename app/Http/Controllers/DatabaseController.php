<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DatabaseController extends Controller
{
    public function index() {
        Gate::allows('isAdmin') ? Response::allow() : abort(403);
        return view('database/database');
    }
}
