<?php
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonnationController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\notificationController;
use App\Http\Controllers\Api\BloodController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\SendEmailController;
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


// route for registration

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

//send email to user
Route::post("sendemail",[SendEmailController::class,"send"]);

//search in volunteers
Route::get('search/{donner}',[DonnationController::class,'search']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// route for login

Route::post('login', [RegisterController::class, 'login']);

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

Route::get('userrequests',[RequestController::class,'UserHasRequests'])->middleware('auth:sanctum');

// route display user posts
Route::get('userposts',[PostController::class,'UserHasPosts'])->middleware('auth:sanctum');

//route to get request notification
Route::get('getRequestNotification',[notificationController::class,'get']);

//route to get user notification
Route::get('getUserNotification',[notificationController::class,'userHasNotification'])->middleware('auth:sanctum');

//route to mark notification as read
Route::post('mark-as-read',[notificationController::class,'markAsRead'])->middleware('auth:sanctum');

//route to edit profile
Route::post('update-profile',[RegisterController::class,'update'])->middleware('auth:sanctum');

//route to get availability of blood
Route::get('blood-availability',[BloodController::class,'availability']);

//route to get last articles
Route::get('last-article',[AdminController::class,'latestArticle']);

//route to get number of applies ber donner
Route::get('donner-applies',[DonnationController::class,'DonnerAplies'])->middleware('auth:sanctum');

//route to get all articles
Route::get('allarticles',[AdminController::class,'allarticles']);

//route to leave feedback
Route::post('feedback',[AdminController::class,'feedback']);

//route to get specific article
Route::get('articles/{articleId}',[AdminController::class,'articleDetails']);

//route to get number of requests overall
Route::get('overall-requests',[RequestController::class,'OverallRequests']);

//route to get liked posts for each user(post_id)
Route::get('liked-posts',[PostController::class,'likedposts'])->middleware('auth:sanctum');

//route to get profile data
Route::get('profile/{userId}',[RegisterController::class,'profile']);

//route to get posts count of user
Route::get('posts-count/{user_id}',[PostController::class,'postsCount']);

//route to get requests count of user
Route::get('requests-count/{user_id}',[RequestController::class,'requestsCount']);

//route to get apply count of user
Route::get('apply-count/{user_id}',[DonnationController::class,'applyCount']);

//route to modify donner data
Route::post('modify-donnation',[DonnationController::class,'updateDonnationData'])->middleware('auth:sanctum');

//route to chat
Route::post('messages',[ChatController::class,'message'])->middleware('auth:sanctum');

//route to get chat messages
Route::get('get-messages',[ChatController::class,'oldChat']);

//route to check if email verified
Route::get('verified',[RegisterController::class,'isVerified'])->middleware('auth:sanctum');

/////////////////////////admin///////////////////////////////////////////////

//route to add admin

Route::post('add-admin',[AdminController::class,'addAdmin'])->middleware(['auth:sanctum','Is_Admin']);

//route to delete Admin
Route::post('delete-admin',[AdminController::class,'deleteAdmin'])->middleware(['auth:sanctum','Is_Admin']);

//route to write new article
Route::post('add-article',[AdminController::class,'addArticle'])->middleware(['auth:sanctum','Is_Admin']);

// route to publish post
Route::post('publishpost/{postId}',[AdminController::class,'publish'])->middleware(['auth:sanctum','Is_Admin']);

//route to delete post
Route::post('delete-post/{postId}',[AdminController::class,'deletePost'])->middleware(['auth:sanctum','Is_Admin']);

// route to delete article
Route::post('delete-article/{articleId}',[AdminController::class,'deleteArticle'])->middleware(['auth:sanctum','Is_Admin']);

//route to get all posts
Route::get('allposts',[PostController::class,'allposts'])->middleware(['auth:sanctum','Is_Admin']);

// route to get all users
Route::get('users',[RegisterController::class,'index'])->middleware(['auth:sanctum','Is_Admin']);

//route to get feedback
Route::get('all-feedback',[AdminController::class,'getFeedback'])->middleware(['auth:sanctum','Is_Admin']);
