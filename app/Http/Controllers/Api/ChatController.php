<?php

namespace App\Http\Controllers\Api;

use App\Events\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function message(Request $request){

       event(new Message($request->input('username'),$request->input('message')));

        return[];
    } 
}
