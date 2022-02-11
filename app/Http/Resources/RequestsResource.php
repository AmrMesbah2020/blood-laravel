<?php

namespace App\Http\Resources;
use App\Models\Apply;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'address'=>$this->address,
            'phone' =>$this->phone,
            'description' =>$this->description,
            'quantity' =>$this->quantity,
            'date' =>$this->date,
            'blood'=>new BloodResource($this->blood),
            'owner_details'=>new UserResource($this->ownerDetails),
            // 'donners'=>$this->numberOfDonners($this->id)
        ];
    }
}
