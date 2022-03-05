<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Apply;
use App\Models\Post;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\Registered;


class RegisterController extends Controller
{

    public function index(){
        $users=User::all();
        return $users;
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->all();

        if(User::where('email',$input['email'])->exists()){
            return response()->json(["this email already has account"],403);
        }else{

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;

        event(new Registered($user));
        return response()->json(["done",$success], 200);
        }
    }



    public function login(LoginRequest $request)

    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();

            $success['token'] =  $user->createToken('MyApp')->plainTextToken;

           return response()->json($success['token'] , 200);

        }else{
            return response()->json("invalid email or password" , 403);
        }

    }

    public function update(UpdateRequest $request)
    {

        $input=$request->all();

        // if(!User::where('email',$input['email'])->exists()){

        if ($request->file('avatar')) {
            $avatarURL = cloudinary()->upload($request->file('avatar')->getRealPath())->getSecurePath();
            User::where('id',$request->user()->id)->update(['avatar'=>$avatarURL]);
        }

        User::where('id',$request->user()->id)->update([
            'name'=>$input['name'],
            'email'=>$input['email'],
            'address'=>$input['address'],
            'wieght'=>$input['wieght'],
            'phone'=>$input['phone'],

        ]);
    // }else{
    //     return response()->json('This Email already has Account');
    // }
    }

    public function calcAge($birtdate){

        (int)$years=Carbon::parse($birtdate)->diff(Carbon::now())->format('%y');
        return (int)$years;
    }

    public function profile($userId){
        $user=User::find($userId);
        $postsCount=Post::where([['user_id',$userId],['access',true]])->count();
        $requestsCount=RequestModel::where('owner_id',$userId)->count();
        $appliesCount=Apply::where('donner_id',$userId)->count();

        return [new UserResource($user),$postsCount, $requestsCount,$appliesCount];
    }

    public function isVerified(Request $request){
        return $request->user()->hasVerifiedEmail();
        // dd($request->user()->hasVerifiedEmail());
    }



}
