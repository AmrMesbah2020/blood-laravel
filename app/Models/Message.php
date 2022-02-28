<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    protected $table = 'chat';

    protected $fillable=[
        'message',
        'sender'
    ];


    public function sender(){
        return $this->belongsTo(User::class);
    }
}
