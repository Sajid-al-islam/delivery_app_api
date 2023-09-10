<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function driver_lists() {
        $driver_lists = User::where('type', 'driver')->where('status', 1)->get();

        return response()->json($driver_lists, 200);
    }
}
