<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResouce;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    public function show()
    {
        return new UserResouce(auth()->user());
    }
}
