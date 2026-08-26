<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\ChatInfo\ChatInfoSyncDto;
use App\Services\ChatInfo\ChatInfoServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ChatInfo\ChatInfoSyncRequest;
use Illuminate\Support\Facades\Auth;

class ChatInfoController extends Controller
{
    public function __construct
    (
        private readonly ChatInfoServiceInterface $chatInfoService
    )
    {
    }

    public function sync(ChatInfoSyncRequest $request)
    {
        $token = $this->chatInfoService->sync(new ChatInfoSyncDto(Auth::user()->id, ...$request->validated()));
        return $this->dataResponse(["token" => $token]);
    }
}
