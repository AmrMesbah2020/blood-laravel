<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable=[
        'phone',
        'description',
        'quantity',
        'date',
        'address',
        'owner_id',
        'applies',
        'blood_id',
    ];


}