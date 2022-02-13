<?php

namespace App\Http\Controllers\Api;

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

        $request = RequestModel::create([
            'phone' => $input['phone'],
            'description' => $input['description'],
            'quantity' => $input['quantity'],
            'owner_id' => $request->user()->id,
            'date' => $input['date'],
            'address' => $input['address'],
            'blood_id' => $blood_id[0],

        ]);
        $userID = auth()->user()->id;
        $user = User::where('id', $userID)->first();
        $user->notify(new postNewNotification($request));
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
            Apply::select(Apply::raw('count(donner_id)'))->where('request_id', $request_id)
            ->groupBy('request_id')
            ->pluck('count(donner_id)');
    }

    public function UserHasRequests(Request $request)
    {
        $requests = RequestModel::where('owner_id',$request->user()->id)->get();
        return $requests;
    }
}
