<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Comment\UpdateCommentStatusRequest;
use App\Http\Resources\V1\Comment\CommentCollection;
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
        return $this->dataResponseCollection(new CommentCollection($this->commentService->dataTable()));
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
