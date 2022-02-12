<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'request_id';
    use HasFactory;
    protected $fillable=[
        'phone',
        'description',
        'quantity',
        'date',
        'address',
        'owner_id',
        'blood_id',
    ];

    public function ownerDetails()
    {
       $user= $this->belongsTo(User::class,'owner_id');
        return $user ;

    }

    public function blood()
    {
        return  $this->belongsTo(Blood::class,'blood_id');
    
    }

   




  
}
