<?php

namespace App\Http\Controllers\V1\Shop;

use App\Events\NewContactEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Contact\StoreContactRequest;
use App\Services\Contact\ContactServiceInterface;

class ContactController extends Controller
{
    public function __construct(private readonly ContactServiceInterface $contactService)
    {
    }

    public function store(StoreContactRequest $request)
    {
        $this->contactService->store($request->get("name") , $request->get("concept") , $request->get("mobile") , $request->get("message") , $request->get("city_id") , $request->get("province_id"));
        event(new NewContactEvent());
        return $this->successResponse(__("action.submit",["attr"=>__("attr.message")]));
    }
}
