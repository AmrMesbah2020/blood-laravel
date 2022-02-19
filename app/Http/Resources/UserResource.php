<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'age'=> app('App\Http\Controllers\Api\RegisterController')->calcAge($this->birthdate),
            'address'=>$this->address,
            'gender'=>$this->gender,
            'phone'=>$this->phone,
            'avatar'=>$this->avatar,
            'wieght'=>$this->wieght,
            'donnation_data'=>new DonnerResource($this->profile),
        ];
    }
}
