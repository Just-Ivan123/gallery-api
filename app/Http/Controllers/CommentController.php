<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request)
    {
        try {
            $comment = $this->commentService->createComment($request);
            return $comment;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(CommentRequest $request, $id)
    {
        $comment = $this->commentService->updateGallery($request, $id);
        return response()->json($comment);
    }

    public function destroy($id)
    {
        return $this->commentService->deleteComment($id);
    }
}
