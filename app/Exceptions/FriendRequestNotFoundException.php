<?php

namespace App\Exceptions;

use Exception;

class FriendRequestNotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                'code' => '404' ,
                'title' => 'Friend request not found!',
                'detail' => 'Unable to request the friend request with the given information.'
            ]
        ],404); 
    }
}
