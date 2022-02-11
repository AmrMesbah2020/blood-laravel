<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;

        // event(new Registered($user));
        return response()->json(["done",$success], 200);
    }



    public function login(LoginRequest $request)

    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();

            $success['token'] =  $user->createToken('MyApp')-> plainTextToken;

           return response()->json($success['token'] , 200);

        }else{
            return "unauthorized";
        }

    }




}
