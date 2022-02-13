<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'userRatePosts';
    protected $primaryKey = 'post_id';

    public $timestamps = false;

    protected $fillable=[
        'user_id',
        'post_id',
        'rate',
    ];

    public function post()
    {
       $post= $this->belongsTo(Post::class,'user_id');
        return $post ;

    }

}
