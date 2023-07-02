<?php

namespace App\Services; 

use App\Http\Requests\CommentRequest;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;

class CommentService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createComment(CommentRequest $request)
    {
        
        $validatedData = $request->validated();

        $user = Auth::user();
        $comment = $user->comments()->create([
            'content' => $validatedData['content'],
            'gallery_id' => $validatedData['gallery_id'],
        ]);
        $comment->load('user');
        return $comment;
    }

    public function deleteComment($comment_id){
        $user = Auth::user();
        $comment = $user->comments()->findOrFail($comment_id);

        $comment->delete();

        return $comment;
    }
}