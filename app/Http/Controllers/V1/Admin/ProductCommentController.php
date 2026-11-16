<?php

namespace App\Http\Controllers\V1\Admin;

use App\Enums\CommentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\SaveProductCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Product;

class ProductCommentController extends Controller
{
    private function comments(int $id)
    {
        Product::findOrFail($id);
        return Comment::where('product_id', $id)->whereNull('user_id')->whereNotNull('author_name');
    }

    public function index(int $id)
    {
        return $this->dataResponseCollection(CommentResource::collection($this->comments($id)->latest('id')->get()));
    }

    public function store(int $id, SaveProductCommentRequest $request)
    {
        Product::findOrFail($id);
        $comment = Comment::create([
            ...$request->validated(), 'product_id' => $id, 'user_id' => null,
            'status' => CommentStatus::Accepted->value,
        ]);
        return $this->dataResponse(new CommentResource($comment));
    }

    public function update(int $id, int $commentId, SaveProductCommentRequest $request)
    {
        $comment = $this->comments($id)->findOrFail($commentId);
        $comment->update($request->validated());
        return $this->dataResponse(new CommentResource($comment));
    }

    public function destroy(int $id, int $commentId)
    {
        $this->comments($id)->findOrFail($commentId)->delete();
        return $this->successResponse(__("action.remove", ['attr' => __("attr.comment")]));
    }
}
