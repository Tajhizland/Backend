<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Contact\StoreContactRequest;
use App\Services\Contact\ContactServiceInterface;
use Illuminate\Support\Facades\Lang;

class ContactController extends Controller
{
    public function __construct(private ContactServiceInterface $contactService)
    {
    }

    public function store(StoreContactRequest $request)
    {
        $this->contactService->store($request->get("name") , $request->get("email") , $request->get("message") , $request->get("city_id") , $request->get("province_id"));
        return $this->successResponse(Lang::get("action.submit",["attr"=>Lang::get("attr.message")]));
    }
}
