<?php

namespace App\Http\Controllers;

use App\Friend;
use App\Http\Resources\Friend as FriendResource;
use Illuminate\Http\Request;

class FriendRequestResponseController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'user_id' => '',
            'status' => ''
        ]);
        // dd($data);

        $friend_request = Friend::where('user_id', $data['user_id'])->where('friend_id', auth()->user()->id)->firstOrfail();
        $friend_request->update(array_merge($data, ['confirmed_at' => now()]));

        return new FriendResource($friend_request);
    
    }
}
