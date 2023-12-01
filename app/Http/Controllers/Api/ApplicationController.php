<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Position;
use App\Models\Status;
use App\Models\User;
use App\Notifications\AcceptedForPosition;
use App\Notifications\ApplicationEnded;
use App\Notifications\InterviewInvitation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request): Collection
    {
        $user_id = Auth::id();
        $search_quary = $request->search ?? null;

        $applications = Application::query()
            ->with(['position', 'status'])
            ->where('user_id', $user_id)
            ->whereHas('position', function ($query) use ($search_quary) {
                $query->where('name', 'like', "%" . $search_quary . "%");
            })
            ->get();

        return $applications;
    }

    public function store(Request $request): int
    {
        $application = new Application();
        $application->user_id = Auth::id();
        $application->position_id = $request->input('position_id');
        $application->status_id = 1;
        $application->attachment_text = $request->input('attachment_text') ?? null;
        $application->attachment_file = $request->input('attachment_file') ?? null;
        $application->save();

        return $application->id;
    }

    public function show(string $id): array
    {
        $application = Application::with(['user', 'position.department', 'status'])->findOrFail($id);
        $all_statuses = Status::all();
        return
            compact(
                'application',
                'all_statuses'
            );
    }

    public function end(string $id)
    {
        $application = Application::findOrFail($id);
        if ($application->status_id !== 6) {
            $application->status_id = 6;

            // send notification
            if (Auth::user()->role_id !== 2) {
                $rejected_user = User::findOrFail($application->user_id);
                $rejected_user->notify(new ApplicationEnded(Auth::user(), $application->position->name));
            }

            $application->save();
        } else {
            return ['message' => '404 not authorized'];
        }
    }

    public function move(string $id)
    {
        $application = Application::findOrFail($id);
        $current_status = $application->status_id;
        if ($current_status < 4) {
            $application->status_id = $current_status + 1;
            $application->save();
        } else if ($current_status === 4) {

            // changes application status to hired
            $application->status_id = $current_status + 1;
            $application->position->hiring = 0;

            $user_id = $application->user_id;

            // removes user from his old position
            $old_position = Position::query()->where('user_id', $user_id)->first();
            if ($old_position) {
                $old_position->user_id = null;
                $old_position->save();
            }

            // moves user to new position
            $new_position = Position::findOrFail($application->position_id);
            $new_position->user_id = $user_id;
            $new_position->save();

            // ends other applications for this position
            $rejected_applications = Application::query()->where('position_id', $application->position_id)->get();
            $rejected_users_ids = [];
            foreach ($rejected_applications as $rejected_application) {
                if ($rejected_application->id !== $application->id) {
                    $this->end_application($rejected_application->id);
                    $rejected_users_ids[] = $rejected_application->user_id;
                }
            }

            // notifications
            $accepted_user = User::findOrFail($user_id);
            $accepted_user->notify(new AcceptedForPosition(Auth::user(), $application->position->name));

            $rejected_users = User::query()->whereIn('id', $rejected_users_ids)->get();
            Notification::send($rejected_users, new ApplicationEnded(Auth::user(), $application->position->name));

            $application->save();
        } else {
            return ['message' => '404 not authorized'];
        }
    }

    public function end_application($id): void
    {
        $application = Application::findOrFail($id);
        $application->status_id = 6;
        $application->save();
    }

    public function notify(Request $request): void
    {
        $text = $request->input('text');
        $datetime = $request->input('datetime');
        $place = $request->input('place');
        $user_id = $request->input('applicant_id');
        $sender = $request->input('sender');
        $user = User::findOrFail($user_id);
        $user->notify(new InterviewInvitation($text, $datetime, $place, $sender));
    }
}
