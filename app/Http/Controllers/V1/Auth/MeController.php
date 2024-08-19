<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function me(Request $request)
    {
        if (!$request->user())
            return $this->UnauthorizedResponse("Unauthorized");
        return $this->dataResponse(new UserResource($request->user()));
    }
}
