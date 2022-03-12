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
        if(User::where('email',$input['email'])->exists()){
        User::where('email',$input['email'])->update(['isAdmin'=>2]);
        return response()->json('done',200);
    }else{
        return response()->json('No User With This Email',404);
    }

    }

    public function deleteAdmin(Request $request){
        $input=$request->all();
        if(User::where('email',$input['email'])->exists()){
            $user=User::where('email',$input['email'])->get();
            if($user[0]['isAdmin'] !=0){
        User::where('email',$input['email'])->update(['isAdmin'=>0]);
        return response()->json('not admin now',200);
            }else{
                return response()->json('This User IS not Admin',404);
            }
    }else{
        return response()->json('No User With This Email',404);
    }

    }
    ////////////////////////////////////////////////////////////////////////////////////////
    public function addArticle(ArticleRequest $request)
    {
        $input = $request->all();

        if ( $request->file('image')) {
            $imageURL = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
             Article::create([
                'title' => $input['title'],
                'content' => $input['content'],
                'image' => $imageURL,
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

        return response()->json('done',200);
    }

    public function publish(Request $request, $postId)
    {
        Post::where('post_id', $postId)->update(['access' => 1, 'admin_id' => $request->user()->id]);
        return response()->json('done',200);
    }

    public function deletePost($postId)
    {
        Post::where('post_id', $postId)->forceDelete();
        return response()->json('done',200);
    }

    public function deleteArticle($ArticleId)
    {
        Article::where('article_id', $ArticleId)->delete();
        return response()->json('done',200);
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

        return response()->json("done",200);

    }

    public function articleDetails($articleId){
        return Article::where('article_id',$articleId)->get();

    }

    public function getFeedback(){
        return Feedback::all();
    }
}
