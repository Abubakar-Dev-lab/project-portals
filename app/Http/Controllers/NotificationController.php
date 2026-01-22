<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Get all notifications (Read + Unread) for the history list
        // We paginate so the page stays fast even with 1,000 alerts
        $notifications = $user->notifications()->paginate(15);

        // 2. THE LOGIC: Mark all unread notifications as read
        // This clears the red dot in the navbar
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}
