<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeRequesy;
use App\Http\Resources\RequestsResource;
use App\Http\Resources\DonnerResource;

// use Illuminate\Http\Request;
use App\Models\Request;
use  App\Models\Blood;
use  App\Models\Apply;

use App\Notifications\postNewNotification;
use Notification;

class RequestController extends Controller
{

    public function store(MakeRequesy $request){

        $input = $request->all();
        // dd($input);

        $blood_id= Blood::where([['rhd',$input['rhd']],['blood_group',$input['blood_group']]])->pluck('blood_id');

        // dd($blood_id);
        // dd($request->user()->id);

            Request::create([
            'phone'=>$input['phone'],
            'description'=>$input['description'],
            'quantity'=>$input['quantity'],
            'owner_id'=>$request->user()->id,
            'date'=>$input['date'],
            'address'=>$input['address'],
            'blood_id'=>$blood_id[0],
            ]);

    }


    public function index(){
        $requests=Request::all();
 
        return RequestsResource::collection($requests);
    }

    // public function numberOfDonners(){
       
    //     $donners= Apply::
    //             select(Apply::raw('count(donner_id)'))
    //             ->groupBy('request_id')
    //             ->get();
                
    //         //   return ($donners);
         
    // //    $post = Post::where('post_id',$post_id)->get();

    //    return RequestsResource::collection($donners);

    // }
}
