<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeCollection;
use App\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    /**
     *  Note: 
     *  likes() -> if this relationship this will return the relationship query itself.
     *  likes -> this will return a collection or data. 
     */
    public function store(Post $post)
    {   
        $post->likes()->toggle(auth()->user());

        return new LikeCollection($post->likes);
    }
}
