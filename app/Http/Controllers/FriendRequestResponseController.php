<?php

namespace App\Http\Controllers;

use App\Exceptions\FriendRequestNotFoundException;
use App\Friend;
use App\Http\Resources\Friend as FriendResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FriendRequestResponseController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'user_id' => 'required',
            'status' => 'required'
        ]);

      try {
        $friend_request = Friend::where('user_id', $data['user_id'])
        ->where('friend_id', auth()->user()->id)
        ->firstOrfail();

      } catch (ModelNotFoundException $e) {
        throw new FriendRequestNotFoundException();
      }

        $friend_request->update(array_merge($data, ['confirmed_at' => now()]));

        return new FriendResource($friend_request);
    
    }

    public function destroy()
    {
      $data = request()->validate([
          'user_id' => 'required'
      ]);

    try {

        $friend_request = Friend::where('user_id', $data['user_id'])
        ->where('friend_id', auth()->user()->id)
        ->firstOrfail()
        ->delete();

    } catch (ModelNotFoundException $e) {
        throw new FriendRequestNotFoundException();
    }

      return response()->json([], 204);
    }
}
