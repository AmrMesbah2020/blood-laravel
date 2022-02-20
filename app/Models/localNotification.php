<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class localNotification extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $table = 'notification';
    

    protected $fillable=[
      
        'notification_message',
        'user_id',
        
    ];

}
