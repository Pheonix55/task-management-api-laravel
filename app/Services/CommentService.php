<?php
namespace App\Services;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Auth;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
class CommentService
{
    public function getComment($task_id = null, $user_id = null)
    {
        $query = Comment::query();
        if ($task_id) {
            $query->where('task_id', $task_id);
        }
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        $data = $query->get();
        return CommentResource::collection($data);
    }


    public function storeComment(array $data, $id)
    {
        $data['user_id'] = Auth::user()->id;
        $data['task_id'] = $id;
        $comment = Comment::create($data);
        // $comment->load('task');
        // $comment->load('user');
        return new CommentResource($comment);
    }

}