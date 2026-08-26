<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contact\ContactResource;
use App\Services\Contact\ContactServiceInterface;

class ContactController extends Controller
{
    public function __construct(private readonly ContactServiceInterface $contactService)
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(ContactResource::collection($this->contactService->dataTable()));
    }

    public function find($id)
    {
        return $this->dataResponse(new ContactResource($this->contactService->find($id)));
    }

    public function remove($id)
    {
        $this->contactService->remove($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.message")]));
    }
}
