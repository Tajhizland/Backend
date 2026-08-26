<?php

namespace App\Http\Controllers\V1\Shop;

use App\Events\CommentSubmitEvent;
use App\DTOs\Comment\CommentStoreDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Comment\StoreCommentRequest;
use App\Services\Comment\CommentServiceInterface;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    public function __construct
    (
        private readonly CommentServiceInterface $commentService
    )
    {
    }

    public function store(StoreCommentRequest $request)
    {
        $this->commentService->createComment(new CommentStoreDto(Auth::user()->id, ...$request->validated()));
        event(new CommentSubmitEvent());
        return $this->successResponse(__("action.send",["attr"=>__("attr.comment")]));
    }
}
