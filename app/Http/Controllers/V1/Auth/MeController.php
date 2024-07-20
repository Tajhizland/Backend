<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function me(Request $request)
    {
        if (!$request->user())
            return $this->UnauthorizedResponse("Unauthorized");
        return $this->dataResponse(["me" => $request->user()]);
    }
}
