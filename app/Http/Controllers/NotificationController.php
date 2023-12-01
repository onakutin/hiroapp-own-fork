<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ContactUser;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;


class NotificationController extends Controller
{
    public function send(Request $request): void
    {
        $user = User::where('email', $request->input('to'))->first();
        $to = $request->input('to');
        $from = $request->input('from');
        $subject = $request->input('subject');
        $text = $request->input('text');
        Notification::send($user, new ContactUser($from, $to, $text, $subject));
    }

    public function get_unread_count(): DatabaseNotificationCollection
    {
        $notifications = Auth::user()->notifications;
        return $notifications->sortByDesc('created_at') ?? null;
    }

    public function mark_as_read(Request $request): array
    {
        $notification = Auth::user()
            ->notifications
            ->where('id', $request->input('id'))
            ->where('read_at', null)
            ->first();
        if ($notification) {
            $notification->markAsRead();
        } else {
            return ['message' => 'error'];
        }
        return ['message' => 'success'];
    }
}
