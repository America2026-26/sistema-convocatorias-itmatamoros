<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'fecha_desde' => ['nullable', 'date'],
            'fecha_hasta' => ['nullable', 'date', 'after_or_equal:fecha_desde'],
        ]);

        $notifications = $request->user()->notifications()
            ->reorder()
            ->when(
                filled($filters['fecha_desde'] ?? null),
                fn ($query) => $query->whereDate('created_at', '>=', $filters['fecha_desde']),
            )
            ->when(
                filled($filters['fecha_hasta'] ?? null),
                fn ($query) => $query->whereDate('created_at', '<=', $filters['fecha_hasta']),
            )
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();
        $notifications->getCollection()->each(function ($notification): void {
            if (! $notification->read_at) {
                $notification->markAsRead();
            }
        });

        return view('notifications.index', [
            'notifications' => $notifications,
            'filters' => [
                'fecha_desde' => $filters['fecha_desde'] ?? '',
                'fecha_hasta' => $filters['fecha_hasta'] ?? '',
            ],
        ]);
    }

    public function show(Request $request, string $notificationId): View
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return view('notifications.show', compact('notification'));
    }

    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->route('notifications.index');
    }
}
