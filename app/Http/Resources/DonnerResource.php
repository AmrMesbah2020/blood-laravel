<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonnerResource extends JsonResource
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
            'last_date_of_donnation'=>$this->last_date_of_donnation,
            'blood_id'=>new BloodResource($this->blood),
            'donner_data'=>app('App\Http\Controllers\Api\DonnationController')->donnerData($this->donner_id)
        ];
    }
}
