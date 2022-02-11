<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'content',
        'image',
        'user_id',
        'admin_id',
        'access',
    ];

    public function rate()
    {
        return $this->belongsTo(Rating::class,'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
