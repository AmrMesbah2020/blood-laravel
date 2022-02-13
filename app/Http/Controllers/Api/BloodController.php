<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\BloodResource;
use Illuminate\Http\Request;
use App\Models\Blood;

class BloodController extends Controller
{
    public function availability()
    {
        $blood = Blood::select('*')->orderbyDesc('availability')->get('availability');
        return BloodResource::collection($blood);
    }

}
