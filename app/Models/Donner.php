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
        return $this->hasOne(User::class,'id');
    }

    public function blood(){
        return $this->belongsTo(Blood::class,'blood_id');
    }



}
