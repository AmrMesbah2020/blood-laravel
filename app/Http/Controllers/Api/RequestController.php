<?php

namespace App\Http\Controllers\Api;

use App\Events\RequestNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\MakeRequesy;
use App\Http\Resources\RequestsResource;
use App\Http\Resources\DonnerResource;

use Illuminate\Http\Request;

use App\Models\Request as RequestModel;
use  App\Models\Blood;
use  App\Models\Apply;
use App\Models\User;
use App\Notifications\postNewNotification;
use Illuminate\Support\Facades\Log;
use Notification;


class RequestController extends Controller
{
    private $notificationController;

    public function __construct(notificationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }

    public function store(MakeRequesy $request)
    {

        $input = $request->all();
        // dd($input);

        $blood_id = Blood::where([['rhd', $input['rhd']], ['blood_group', $input['blood_group']]])->pluck('blood_id');

        // dd($blood_id);
        // dd($request->user()->id);


         $requestSent=RequestModel::create([
            'phone' => $input['phone'],
            'description' => $input['description'],
            'quantity' => $input['quantity'],
            'owner_id' => $request->user()->id,
            'date' => $input['date'],
            'address' => $input['address'],
            'blood_id' => $blood_id[0],

        ]);

        event(new RequestNotification($request->user()->name.' need blood of type '.$input['blood_group'].$input['rhd']));
        // $userID = auth()->user()->id;
        // $user = User::where('id', $userID)->first();
        // $user->notify(new postNewNotification($requestSent));
        // $this->notificationController->send($request->request_id);

    }



    public function index()
    {
        $requests = RequestModel::all();

        return RequestsResource::collection($requests);
    }

    public function numberOfDonners($request_id)
    {
        return
            Apply::select('donner_id')->where("request_id",$request_id)->count();
    }

    public function UserHasRequests(Request $request)
    {
        $requests = RequestModel::where('owner_id',$request->user()->id)->get();
        return [RequestsResource::collection($requests),RequestModel::where('owner_id',$request->user()->id)->count()];
    }

    public function OverallRequests(){
       return RequestModel::select('request_id')->count();
    }

    public function requestsCount($id){
        return RequestModel::where('owner_id',$id)->count();
     }
}
