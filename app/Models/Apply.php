<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    public $timestamps=false;
    use HasFactory;

    protected $table = 'donnerApplyRequest';



    protected $fillable =[
        'request_id',
        'donner_id',
        'applies'
    ];

   
}
