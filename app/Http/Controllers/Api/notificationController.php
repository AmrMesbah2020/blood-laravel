<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\User;
use App\Models\notifications;
use App\Notifications\postNewNotification;
use Notification;
// use Illuminate\Notifications\Notification;


class notificationController extends Controller
{
   
    public function send($id){
        

    $users=User::get();

    $request=Request::where('request_id', $id)->first();
    Notification::send($users,new postNewNotification($request));
    }

    public function get(){
        // $notifications=notifications::get();
        $notifications = notifications::select('data')->orderBy('created_at', 'DESC')->first();

        return $notifications;
    }
}
