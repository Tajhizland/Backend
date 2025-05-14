<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\ChatInfo\ChatInfoSyncRequest;
use App\Services\ChatInfo\ChatInfoService;
use Illuminate\Support\Facades\Auth;

class ChatInfoController extends Controller
{
    public function __construct
    (
        private ChatInfoService $chatInfoService
    )
    {
    }

    public function sync(ChatInfoSyncRequest $request)
    {
        $token = $this->chatInfoService->sync(Auth::user()->id, $request->get("token"));
        return $this->dataResponse(["token" => $token]);
    }
}
