<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($id, CommentService $service)
    {
        $data = $service->getComment(task_id: $id, user_id: Auth::user()->id);
        return $data->isNotEmpty() ? $this->successResponse($data) : $this->errorResponse('no comments found');
    }
    public function store(CommentRequest $request, CommentService $service, $id)
    {
        $data = $service->storeComment($request->validated(), $id);
        return $this->successResponse($data);
    }
}

