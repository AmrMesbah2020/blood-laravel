<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Rating;
use App\Http\Resources\PostResource;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Collection;

class PostController extends Controller
{


    public function store(PostRequest $request)
    {

        $input = $request->all();

        if ($path = $request->file('image')) {
            $path = $request->file('image')->store('post_images');
        }

        Post::create([
            'title' => $input['title'],
            'content' => $input['content'],
            'image' => $path,
            'user_id' => $request->user()->id,
        ]);
    }

    public function rate(Request $request, $postId)
    {

        $input = $request->all();
        if (Rating::where([['post_id', $postId], ['user_id', $request->user()->id]])->exists()) {

            $res = Rating::where([['post_id', $postId], ['user_id', $request->user()->id]])->delete();
        } else {
            Rating::insert([

                'post_id' => $postId,
                'user_id' => $request->user()->id,

            ]);
            $rate = Rating::where('post_id', $postId)->pluck('rate');

            Rating::where([['user_id', $request->user()->id], ['post_id', $postId]])->increment('rate');
        }
    }

    public function allposts()
    {
        $posts = Post::all();
        return PostResource::collection($posts);
    }

    public function posts()
    {
        $posts = Post::where('access', true)->get();
        return PostResource::collection($posts);
    }


    public function topRatedPost()
    {

        $posts = Rating::select(Rating::raw('post_id'))
            ->groupBy('post_id')
            ->orderByDesc(Rating::raw('count(post_id)'))
            ->limit(1)
            ->get();

        $post_id = $posts[0]->post_id;

        $post = Post::where('post_id', $post_id)->get();


        return PostResource::collection($post);
    }

    public function post($postId)
    {
        $post = Post::find($postId);
        return new PostResource($post);
    }

    public function UserHasPosts(Request $request)
    {
        $posts = Post::where([['user_id', $request->user()->id],['access',true]])->orderByDesc('created_at')->limit(5)->get();
        return PostResource::collection($posts);
    }

    public function postRate($post_id)
    {
        return Rating::select('post_id')->where('post_id',$post_id)->count();
    }



}
