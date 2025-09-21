<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->back();
        }

        $ids = collect($request->input('ids', []))
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return redirect()->back();
        }

        $notifications = Auth::user()
            ->unreadNotifications()
            ->whereIn('id', $ids)
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->back();
    }
}