<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeRequesy;
use Illuminate\Http\Request;

class RequestController extends Controller
{

    public function store(MakeRequesy $request){

        $input = $request->all();
        // dd($input);

        $blood_id= \App\Models\Blood::where([['rhd',$input['rhd']],['blood_group',$input['blood_group']]])->pluck('blood_id');

        // dd($blood_id);
        // dd($request->user()->id);

        \App\Models\Request::create([
            'phone'=>$input['phone'],
            'description'=>$input['description'],
            'quantity'=>$input['quantity'],
            'owner_id'=>$request->user()->id,
            'date'=>$input['date'],
            'address'=>$input['address'],
            'blood_id'=>$blood_id[0],
        ]);

    }
}
