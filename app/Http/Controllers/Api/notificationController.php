<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\User;
use App\Notifications\postNewNotification;
use Notification;
// use Illuminate\Notifications\Notification;


class notificationController extends Controller
{
   
    public function send(){

    $users=User::get();

    // $requests=Request::where('request_id', 1)->get();

    Notification::send($users,new postNewNotification(Request::where('request_id', 1)));

    }
}
