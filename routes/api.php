<?php

use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonnationController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\notificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = User::where('id',$request->user()->id)->get();
    return UserResource::collection($user);
});


// route ro registration

Route::post('register', [RegisterController::class, 'register']);
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// route for login

Route::post('login', [RegisterController::class, 'login']);

// route to get all users

Route::get('users',[RegisterController::class,'index']);

//route to make request

Route::post('request',[RequestController::class,'store'])->middleware('auth:sanctum');

// route to be donner

Route::post('donnation',[DonnationController::class,'store'])->middleware('auth:sanctum');

//route to make post

Route::post('post',[PostController::class,'store'])->middleware('auth:sanctum');

// route to userRatePosts

Route::post('rate/{postId}',[PostController::class,'rate'])->middleware('auth:sanctum');

//route to get top rated
Route::get('toprated',[PostController::class,'topRatedPost']);

//route to get all posts

Route::get('allposts',[PostController::class,'allposts']);

//route to get specific post

Route::get('post/{postId}',[PostController::class,'post']);

// route to get available posts "posts has access true"

Route::get('posts',[PostController::class,'posts']);

//route to get all donners "with all data"

Route::get('donners',[DonnationController::class,'donners']);

// route allow donner to apply request

Route::post('apply/{request}',[DonnationController::class,'apply'])->middleware('auth:sanctum');

//  route display all requests

Route::get('allrequests',[RequestController::class,'index']);

// route display user requests

Route::get('userrequests',[RequestController::class,'UserHasRequests']);

//route to add notification
// Route::post('addNotification',[notificationController::class,'send'])->middleware('auth:sanctum');

//route to get notification
Route::get('getNotification',[notificationController::class,'get']);



/////////////////////////admin///////////////////////////////////////////////


















