<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function me()
    {
        $me=Auth::user();
        return $this->dataResponse($me);
    }
}
