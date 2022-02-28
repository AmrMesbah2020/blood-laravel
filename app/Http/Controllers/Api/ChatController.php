<?php

namespace App\Http\Controllers\Api;

use App\Events\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message as MessageModel;
use App\Http\Resources\Messages;

class ChatController extends Controller
{
    public function message(Request $request){

       event(new Message($request->user()->name,$request->input('message')));

       MessageModel::create([
            'sender'=>$request->user()->id,
            'message'=>$request->input('message')
       ]);

    }


    public function oldChat(){
      $messages= MessageModel::select('*')->limit(20)->orderByDesc('created_at')->get();
      return Messages::collection($messages);
    }
}
