<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\localNotification;
use App\Models\Request as ModelRequest;
use App\Models\notifications;
use App\Models\User;
use App\Notifications\postNewNotification;
use Illuminate\Http\Request;
use Notification;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Notifications\Notification;


class notificationController extends Controller
{

    public function send($id){


    $users=User::get();

    $request=ModelRequest::where('request_id', $id)->first();
    Notification::send($users,new postNewNotification($request));
    ;

    }



    public function get(){
        // $notifications=notifications::get();
        $notifications = notifications::select('data')->orderBy('created_at', 'DESC')->first();
        return $notifications;
    }



    public function userHasNotification(Request $request){
          return [localNotification::where([['status',0],['user_id',$request->user()->id]])->get(),
                  localNotification::where([['status',0],['user_id',$request->user()->id]])->count()];


// dd($request->user()->id);
        // dd(localNotification::where([['status',0],['user_id',$request->user()->id]])->get());
    }

    public function markAsRead(Request $request){
       if(localNotification::where([['status',0],['user_id',$request->user()->id]])->exists()){
        localNotification::where([['status',0],['user_id',$request->user()->id]])->update(['status'=>1]);
       }else{
           return response()->json('not found');
       }
    }
}
