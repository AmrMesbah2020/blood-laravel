<?php

namespace App\Http\Resources;

use App\Models\User;

use Illuminate\Http\Resources\Json\JsonResource;

class Messages extends JsonResource
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
            'message'=>$this->message,
            'sender'=>User::where('id',$this->sender)->pluck('name')[0],
            'created_at'=>$this->created_at,
        ];
    }
}
