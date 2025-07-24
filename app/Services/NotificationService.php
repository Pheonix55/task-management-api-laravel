<?php
namespace App\Services;

use Auth;
class NotificationService
{
    public function markNotificationsAsRead($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();
        // dd($id, $notification, Auth::user()->notifications->pluck('id'));

        if (!$notification) {
            return false;
        }

        $notification->markAsRead();
        return true;
    }
    public function markAllNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

}