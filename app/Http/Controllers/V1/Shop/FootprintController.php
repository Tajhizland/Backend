<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\FootprintRequest;
use App\Models\Footprint;
use Illuminate\Http\Request;

class FootprintController extends Controller
{
    public function handle(Request $request2, FootprintRequest $request)
    {
        Footprint::create([
            "page" => $request->get("path"),
            "ip" => $request2->ip(),
            "user_id" => $request->get('user_id')
        ]);
    }
}
