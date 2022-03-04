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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DonnationController extends Controller
{


    public function store(DonationRequest $request){


        $input = $request->all();
        $blood_id= Blood::where([['rhd',$input['rhd']],['blood_group',$input['blood_group']]])->pluck('blood_id');
        if(Donner::where('donner_id',$request->user()->id)->exists()){
            return response()->json("You are Already Donner",200) ;
        }else{
        Donner::create([
            'last_date_of_donnation'=>$input['last_date_of_donnation'],
            'donner_id'=>$request->user()->id,
            'blood_id'=>$blood_id[0],
        ]);
        Blood::where('blood_id',$blood_id)->increment('availability');
        return response()->json("done",200) ;

    }
    }

    public function updateDonnationData(Request $request){
        $input=$request->all();
        $blood_id= Blood::where([['rhd',$input['rhd']],['blood_group',$input['blood_group']]])->pluck('blood_id');
        Donner::where('donner_id',$request->user()->id)->update([
            'last_date_of_donnation'=>$input['last_date_of_donnation'],
            'blood_id'=>$blood_id[0],
        ]);
        Blood::where('blood_id',$blood_id)->increment('availability');
        return response()->json('Modified Successfully',200);
    }

    public function donners(){
        $donners = Donner::with('user')->get();
        return DonnerResource::collection($donners);
    }


    public function avilabilityOfDonnation($id){
        $last_date_of_donation=Donner::where('donner_id',$id)->pluck('last_date_of_donnation');
        $days=Carbon::parse(date($last_date_of_donation[0]))->diff(Carbon::now())->format('%a');
        return (int)$days;
    }

    public function ifDonner($id){
       return Donner::where('donner_id',$id)->exists();
    }


    public function apply(Request $request,$request_id){
        if($this->ifDonner($request->user()->id)){
            if($this->avilabilityOfDonnation($request->user()->id)<56){
                $waitingDays=56-$this->avilabilityOfDonnation($request->user()->id);
                    return response()->json("For Your Health Please wait at least ${waitingDays} day",200);
            }else{
                if(Apply::where([['request_id',$request_id],['donner_id',$request->user()->id]])->exists()){
                    return response()->json("already applied",406);
                }else{
                    $doonerName=User::where('id',$request->user()->id)->pluck('name');
                    $requestDescription=ModelsRequest::where('request_id',$request_id)->pluck('description');
                    $request_owner=ModelsRequest::where('request_id',$request_id)->pluck('owner_id');
                    Apply::insert([
                        'request_id' => $request_id,
                        'donner_id' => $request->user()->id,
                        ]);
                    localNotification::insert([
                        'notification_message' =>$doonerName[0] .' Apply your request ' .$requestDescription[0],
                        'user_id'=> $request_owner[0],
                        'donner_id'=>$request->user()->id,
                ]);}
                return response()->json('Thank You',200);
                }
        }
        else{
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

    public function search($name){
 
        $ResultBlood= DB::table('donners')
        ->join('blood', 'blood.blood_id', '=', 'donners.blood_id')
        ->join('users', 'donners.donner_id', '=', 'users.id')
        ->whereRaw("CONCAT(`blood_group`, `rhd`) = ?", [$name])
        ->orWhereRaw("blood_group like '%".$name."%'")
        ->get();
        return response()->json($ResultBlood);
 
 }
}
