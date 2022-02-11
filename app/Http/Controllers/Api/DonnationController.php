<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationRequest;
use Illuminate\Http\Request;
use App\Models\Blood;
use App\Models\Donner;
use App\Models\Apply;

class DonnationController extends Controller
{


    public function store(DonationRequest $request){

        $input = $request->all();
        dd($request->user());
        $blood_id= Blood::where([['rhd',$input['rhd']],['blood_group',$input['blood_group']]])->pluck('blood_id');

        Donner::create([
            'last_date_of_donnation'=>$input['last_date_of_donnation'],
            'donner_id'=>$request->user()->id,
            'blood_id'=>$blood_id[0],
        ]);
    }

    public function donners(){
        $donners = Donner::with('user')->get();
        return $donners;
    }

    public function apply(Request $request,$request_id){
        $input = $request->all();
        if(Apply::where('request_id',$request_id )->exists()){
            Apply::where([['donner_id', $request->user()->id],['request_id', $request_id]])->increment('applies');
        }else{
            Apply::insert([
            'request_id' => $request_id,

            'donner_id' => $request->user()->id,
            ]);
            $applies =Apply::where('request_id',$request_id)->pluck('applies');

            Apply::where([['donner_id', $request->user()->id],['request_id', $request_id]])->increment('applies');
            }
    }
}