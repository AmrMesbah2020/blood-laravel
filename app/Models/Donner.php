<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donner extends Model
{
    use HasFactory;

    protected $fillable=[
        'last_date_of_donnation',
        'donner_id',
        'blood_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class,'donner_id');
    }

}
