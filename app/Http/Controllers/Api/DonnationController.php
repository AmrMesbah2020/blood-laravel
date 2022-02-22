<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationRequest;
use App\Http\Resources\DonnerResource;
use Illuminate\Http\Request;
use App\Models\Blood;
use App\Models\Donner;
use App\Models\Apply;
use App\Models\localNotification;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use App\Models\userNotification;

class DonnationController extends Controller
{


    public function store(DonationRequest $request){


        $input = $request->all();
        $blood_id= Blood::where([['rhd',$input['rhd']],['blood_group',$input['blood_group']]])->pluck('blood_id');
        if(Donner::where('donner_id',$request->user()->id)->exists()){
            return "already exist ya 3sl";
        }else{
        Donner::create([
            'last_date_of_donnation'=>$input['last_date_of_donnation'],
            'donner_id'=>$request->user()->id,
            'blood_id'=>$blood_id[0],
        ]);
        Blood::where('blood_id',$blood_id)->increment('availability');
    }
    }

    public function donners(){
        $donners = Donner::with('user')->get();
        return DonnerResource::collection($donners);
    }


    public function apply(Request $request,$request_id){

        if(Donner::where('donner_id',$request->user()->id)->exists()){
        if(Apply::where([['request_id',$request_id],['donner_id',$request->user()->id]])->exists())
        {
            return response()->json("already applied",406);
        }

       else{
           $doonerName=User::where('id',$request->user()->id)->pluck('name');
           $requestDescription=ModelsRequest::where('request_id',$request_id)->pluck('description');
           $request_owner=ModelsRequest::where('request_id',$request_id)->pluck('owner_id');

        //    dd($requestDescription);

           Apply::insert([

            'request_id' => $request_id,
            'donner_id' => $request->user()->id,
           ]);

           localNotification::insert([
            'notification_message' =>$doonerName[0] .' Apply your request ' .$requestDescription[0],
            'user_id'=> $request_owner[0],
            'donner_id'=>$request->user()->id,
           ]);

    }}else{
        return response()->json("Please make the Eligibility quiz",406);
    }

    }
    public function DonnerAplies(Request $request){

        $input=$request->user()->id;
        return Apply::select(Apply::raw('count(donner_id)'))->where('donner_id',$input)->pluck('count(donner_id)');

    }

    public function donnerData($donnerId){
        return User::find($donnerId);
    }

    public function applyCount($id){
        return Apply::where('donner_id',$id)->count();
    }

}
