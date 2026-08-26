<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\FootprintRequest;
use App\Models\Footprint;

class FootprintController extends Controller
{
    public function handle(FootprintRequest $request)
    {
        $data = $request->validated();
        Footprint::create([
            "page" => $data["path"],
            "ip" => $request->ip(),
            "user_id" => $data["user_id"] ?? null,
        ]);
    }
}
