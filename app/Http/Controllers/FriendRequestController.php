<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Friend;
use App\Http\Resources\Friend as FriendResource;
;use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function store()
    {
        $data = request()->validate(['friend_id' => '']);
        
        /**
         * Steps
         *  - find the id of the user using the friend_id in users table.
         *  - then attach the authenticated user and the id of the friend in the users table.
         *  - return a resource where the user_id = id of the authenticated user and the friend id 
         *      is equal to the requested data friend_id
         *  - then grab the first result.
         */
        
        try {
            User::findOrFail($data['friend_id'])
            ->friends()
            ->attach(auth()->user());
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException();
        }

        return new FriendResource(
            Friend::where('user_id', auth()->user()->id)
                ->where('friend_id', $data['friend_id'])
                ->first()
        ); 
    }
}   
