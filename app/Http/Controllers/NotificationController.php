<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Display full Notification Center page with tabs: Semua, Transaksi, Broadcast.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'semua'); // semua, transaksi, broadcast

        $allNotifications = $this->notificationService->getRecentNotifications($user, 50);

        if ($tab === 'transaksi') {
            $notifications = $allNotifications->where('type', 'transaction')->values();
        } elseif ($tab === 'broadcast') {
            $notifications = $allNotifications->where('type', 'broadcast')->values();
        } else {
            $notifications = $allNotifications;
        }

        $unreadCount = $this->notificationService->getUnreadCount($user);

        return view('notifications.index', compact('notifications', 'tab', 'unreadCount'));
    }

    /**
     * Mark single notification or broadcast as read.
     */
    public function markAsRead(Request $request, string $key)
    {
        $user = Auth::user();
        $success = $this->notificationService->markAsRead($user, $key);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'unread_count' => $this->notificationService->getUnreadCount($user),
            ]);
        }

        return back()->with('success', 'Notifikasi telah ditandai dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $this->notificationService->markAllAsRead($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'Seluruh notifikasi telah ditandai dibaca.');
    }

    /**
     * API endpoint to get unread count and latest items for the top navbar bell dropdown.
     */
    public function getRecent(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['unread_count' => 0, 'items' => []]);
        }

        return response()->json([
            'unread_count' => $this->notificationService->getUnreadCount($user),
            'items' => $this->notificationService->getRecentNotifications($user, 6),
        ]);
    }
}
