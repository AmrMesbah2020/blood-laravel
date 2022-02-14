<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return  [
            'id'=>$this->post_id,
            'title'=>$this->title,
            'content'=>$this->content,
            'image'=>$this->image,
            'owner'=>new UserResource($this->user),
            'rate'=>app('App\Http\Controllers\Api\PostController')->postRate($this->post_id)

    ];
    }
}
