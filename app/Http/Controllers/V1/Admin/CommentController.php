<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use App\Services\Comment\CommentServiceInterface;

class CommentController extends Controller
{
    public function __construct
    (
        private readonly CommentServiceInterface $commentService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(CommentResource::collection($this->commentService->dataTable()));
    }
    public function show($id)
    {
        return $this->dataResponse(new CommentResource($this->commentService->find($id)));
    }

    public function accept($id)
    {
        $this->commentService->accept($id);
        return $this->successResponse(__("action.accept",["attr"=>__("attr.comment")]));
    }

    public function reject($id)
    {
        $this->commentService->reject($id);
        return $this->successResponse(__("action.reject",["attr"=>__("attr.comment")]));
    }
}
