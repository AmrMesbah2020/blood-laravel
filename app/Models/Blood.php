<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blood extends Model
{
    use HasFactory;
    protected $table = 'blood';
    protected $primaryKey = 'blood_id';

    protected $fillable =[
        'blood_group',
        'rhd',
    ];



}
