<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated admin
        $admin = $request->user();

        // Return their notifications (both read and unread)
        return response()->json($admin->notifications()->take(50)->get());
    }

    public function markAsRead(Request $request, $id)
    {
        $admin = $request->user();
        
        if ($id === 'all') {
            $admin->notifications()->delete();
        } else {
            $notification = $admin->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return response()->json(['message' => 'Marked as read']);
    }
}
