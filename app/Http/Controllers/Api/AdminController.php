<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Post;
use App\Http\Requests\ArticleRequest;
use App\Models\Feedback;

class AdminController extends Controller
{
    public function addAdmin(Request $request)
    {
        $input=$request->all();
        User::where('email',$input['email'])->update(['isAdmin'=>1]);
        return 'become admin';
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    public function addArticle(ArticleRequest $request)
    {
        $input = $request->all();

        if ( $request->file('image')) {
             $request->file('image')->store('public');
             Article::create([
                'title' => $input['title'],
                'content' => $input['content'],
                'image' => $request->file('image')->hashName(),
                'resources'=>$input['resources'],
                'admin_id' => $request->user()->id,
            ]);
        }else{
            Article::create([
                'title' => $input['title'],
                'content' => $input['content'],
                'resources'=>$input['resources'],
                'admin_id' => $request->user()->id,
            ]);
        }


    }

    public function publish(Request $request, $postId)
    {
        Post::where('post_id', $postId)->update(['access' => 1, 'admin_id' => $request->user()->id]);
        return response()->json('done');
    }

    public function deletePost($postId)
    {
        Post::where('post_id', $postId)->forceDelete();
        return response()->json('done');
    }

    public function deleteArticle($ArticleId)
    {
        Article::where('article_id', $ArticleId)->delete();
        return response()->json('done');
    }

    public function latestArticle(){

       return Article::select()->orderByDesc('created_at')->limit(3)->get();
    }

    public function allarticles(){

        return Article::all();
    }

    public function feedback(Request $request){

        $input = $request->all();

        Feedback::create($input);

        return response()->json("ya 3asl",200);

    }

    public function articleDetails($articleId){
        return Article::where('article_id',$articleId)->get();

    }

    public function getFeedback(){
        return Feedback::all();
    }
}
