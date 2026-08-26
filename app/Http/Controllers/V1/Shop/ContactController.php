<?php

namespace App\Http\Controllers\V1\Shop;

use App\Events\NewContactEvent;
use App\DTOs\Contact\ContactStoreDto;
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
        $this->contactService->store(new ContactStoreDto(...$request->validated()));
        event(new NewContactEvent());
        return $this->successResponse(__("action.submit",["attr"=>__("attr.message")]));
    }
}
