<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getBaseQuery($req): Builder
    {
        if (Auth::user()->role_id === 3) {
            $department_id = $req->input('department_id');
            $position_ids = Position::where('department_id', $department_id)->select('id')->get();
            if ($req->input('isMonthRestricted')) {
                $currentMonthStart = Carbon::now()->startOfMonth();
                return Application::whereIn('position_id', $position_ids)->where('created_at', '>=', $currentMonthStart);
            } else if ($req->input('isFeedbackRestricted')) {
                return Application::whereIn('position_id', $position_ids)->where('status_id', 4);
            } else if ($req->input('isHiredRestricted')) {
                return Application::whereIn('position_id', $position_ids)->where('status_id', 5);
            } else if ($req->input('isRejectedRestricted')) {
                return Application::whereIn('position_id', $position_ids)->where('status_id', 6);
            } else if ($req->input('isInterviewRestricted')) {
                return Application::whereIn('position_id', $position_ids)->where('status_id', 3);
            } else {
                return Application::whereIn('position_id', $position_ids);
            }
        }
    }

    public function getTotalNumberOfApplications(Request $request): int
    {
        return $this->getBaseQuery($request)->count();
    }

    public function getTotalApplicantsDetail(Request $request): Collection
    {
        return $this->getBaseQuery($request)->get()->load(['user', 'position', 'status']);
    }
}
