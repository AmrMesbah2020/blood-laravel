<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Rating;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{


    public function store(PostRequest $request)
    {
        $input = $request->all();


        if ($request->file('image')) {
            $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
            Post::create([
                'title' => $input['title'],
                'content' => $input['content'],
                'user_id' => $request->user()->id,
                'image' => $imageURL
            ]);
        }else{
            Post::create([
                'title' => $input['title'],
                'content' => $input['content'],
                'user_id' => $request->user()->id,
            ]);
        }
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
        $numberPosts=Post::where([['user_id', $request->user()->id],['access',true]])->count();
        $posts = Post::where([['user_id', $request->user()->id],['access',true]])->orderByDesc('created_at')->limit(5)->get();
        return [PostResource::collection($posts),$numberPosts];
    }

    public function postRate($post_id)
    {
        return Rating::select('post_id')->where('post_id',$post_id)->count();
    }

    public function likedposts(Request $request){
       return Rating::select('post_id')->where('user_id',$request->user()->id)->pluck('post_id');
    }

    public function postsCount($id){
       return Post::where([['user_id',$id],['access',true]])->count();
    }

}
