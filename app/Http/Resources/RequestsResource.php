<?php

namespace App\Http\Resources;

use App\Http\Controllers\Api\DonnationController;
use App\Http\Controllers\Api\RequestController;
use App\Models\Apply;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BloodResource;

class RequestsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->request_id,
            'address'=>$this->address,
            'phone' =>$this->phone,
            'description' =>$this->description,
            'quantity' =>$this->quantity,
            'date' =>$this->date,
            'blood'=>new BloodResource($this->blood),
            'owner_details'=>new UserResource($this->ownerDetails),
            'number_of_donners'=>app('App\Http\Controllers\Api\RequestController')->numberOfDonners($this->request_id),

            // (new RequestController)->numberOfDonners($this->request_id);
            // RequestController::numberOfDonners($this->request_id),

        ];
    }
}
