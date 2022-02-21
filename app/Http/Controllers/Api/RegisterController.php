<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
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
            return response()->json(["this email already has acount"],403);
        }else{

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;

        // event(new Registered($user));
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

        if ($request->file('avatar')) {
            $request->file('avatar')->store('public');
        }

        User::where('id',$request->user()->id)->update([
            'name'=>$input['name'],
            'email'=>$input['email'],
            'address'=>$input['address'],
            'wieght'=>$input['wieght'],
            'phone'=>$input['phone'],
            'avatar'=>$request->file('avatar')->hashName(),
        ]);
    }

    public function calcAge($birtdate){

        (int)$years=Carbon::parse($birtdate)->diff(Carbon::now())->format('%y');
        return (int)$years;
    }

    public function profile($userId){
        $user=User::find($userId);
        return new UserResource($user);
    }

}
