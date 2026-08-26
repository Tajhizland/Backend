<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\UpdateCommentStatusRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Services\Comment\CommentServiceInterface;
use Illuminate\Support\Facades\Lang;

class CommentController extends Controller
{
    public function __construct
    (
        private CommentServiceInterface $commentService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(CommentResource::collection($this->commentService->dataTable()));
    }
    public function findById($id)
    {
        return $this->dataResponse(new CommentResource($this->commentService->findById($id)));
    }

    public function accept(UpdateCommentStatusRequest $request)
    {
        $this->commentService->accept($request->get("id"));
        return $this->successResponse(Lang::get("action.accept",["attr"=>Lang::get("attr.comment")]));
    }

    public function reject(UpdateCommentStatusRequest $request)
    {
        $this->commentService->reject($request->get("id"));
        return $this->successResponse(Lang::get("action.reject",["attr"=>Lang::get("attr.comment")]));
    }
}
