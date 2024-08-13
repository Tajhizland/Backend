<?php

namespace App\Http\Controllers\V1\Shop;

use App\Events\CommentSubmitEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Comment\StoreCommentRequest;
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

    public function store(StoreCommentRequest $request)
    {
        $this->commentService->createComment($request->get("productId"),$request->get("text"),$request->get("rating"));
        event(new CommentSubmitEvent());
        return $this->successResponse(Lang::get("action.send",["attr"=>Lang::get("attr.comment")]));
    }
}
