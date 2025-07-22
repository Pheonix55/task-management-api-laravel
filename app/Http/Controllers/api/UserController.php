<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    public function myNotifications()
    {
        return NotificationResource::collection(auth()->user()->notifications);
    }
    public function readNotification($id, NotificationService $service)
    {

        $success = $service->markNotificationsAsRead($id);
        if ($success) {
            return $this->successResponse();
        } else {
            return $this->errorResponse();
        }

    }
    public function readAllNotification(NotificationService $service)
    {
        try {
            $service->markAllNotificationsAsRead();
            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
