<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Rating;
use Illuminate\Validation\Rules\Exists;

class PostController extends Controller
{


    public function store(PostRequest $request){

        $input=$request->all();

        if($path=$request->file('image')){
        $path = $request->file('image')->store('post_images');
        }

        Post::create([
            'title'=>$input['title'],
            'content'=>$input['content'],
            'image'=>$path,
            'user_id'=>$request->user()->id,
        ]);
    }

    public function rate(Request $request){

        $input=$request->all();
        if(Rating::where('post_id',$input['post_id'])->exists()){
            Rating::where([['user_id', $request->user()->id],['post_id', $input['post_id']]])->increment('rate');
        }else{
        Rating::insert([
            'post_id' => $input['post_id'],
            'user_id' => $request->user()->id,
        ]);

        $rate =Rating::where('post_id',$input['post_id'])->pluck('rate');

        Rating::where([['user_id', $request->user()->id],['post_id', $input['post_id']]])->increment('rate');
        }
    }

    public function allposts(){
        $posts=Post::all();
        return $posts;
    }

    public function posts(){
        $posts=Post::join('userRatePosts', 'posts.post_id', '=', 'userRatePosts.post_id')->where('access',true)->get();
        return $posts;
    }


}
