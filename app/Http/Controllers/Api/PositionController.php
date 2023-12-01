<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function index(Request $request): Collection
    {
        $user_role_id = Auth::user()->role_id;
        if ($user_role_id == 1) {
            $search_quary = $request->search ?? null;
            $department_id = $request->department ?? null;
            if ($department_id != 0) {
                $positions = Position::query()
                    ->with('applications')
                    ->where('hiring', 1)
                    ->where('department_id', $department_id)
                    ->where('name', 'like', "%" . $search_quary . "%")
                    ->get();
            } else {
                $positions = Position::query()
                    ->with('applications')
                    ->where('hiring', 1)
                    ->where('name', 'like', "%" . $search_quary . "%")
                    ->get();
            }
            return $positions;
        } else if ($user_role_id == 2) {
            $search_quary = $request->search ?? null;
            $positions = Position::query()
                ->with('applications')
                ->where('hiring', 1)
                ->where('name', 'like', "%" . $search_quary . "%")
                ->get();
            return $positions;
        } else if ($user_role_id == 3) {
            $user_id = Auth::user()->id;
            $user_position = Position::where("user_id", $user_id)->first();
            $department_id = $user_position->department_id;
            $positions = Position::query()
                ->where('department_id', $department_id)
                ->where('hiring', 1)
                ->with('applications')
                ->get();
            return $positions;
        }
    }

    public function store(Request $request): array
    {
        $position = new Position();
        $position->user_id = null;
        $position->department_id = $request->department_id;
        $position->grade_id = $request->pay_grade;
        $position->name = $request->name;
        $position->description = $request->description;
        $position->hiring = 1;
        $position->start_date = $request->start_date ?? null;
        $position->end_date = $request->end_date ?? null;
        $position->save();
        return
            [
                'message' => 'succes',
                'id' => $position->id,
            ];
    }

    public function show(string $id): array|Position
    {
        if (Auth::user()->role_id === 2) {
            $position = Position::query()->where('id', $id)->with(['department', 'grade'])->first();
            return $position;
        } else {
            $position = Position::findOrfail($id);
            $application = Application::where('position_id', $position->id)->with(['user', 'status'])->get();
            return ['position' => $position->load(['grade', 'user', 'department']), 'applications' => $application];
        }
    }

    public function getAllPositions(): Collection
    {
        $positions = Position::select('name')->distinct()->orderBy('name')->get();
        return $positions;
    }
    public function getPositionsByDepartment($department_id): Collection
    {
        $positions = Position::select('name', 'id')->distinct()->orderBy('name')->where('hiring', 1)->where('department_id', $department_id)->get();
        return $positions;
    }

    public function destroy($id): string
    {
        // Have to add notification
        Application::query()->where('position_id', $id)->delete();
        Position::query()->where('id', $id)->delete();
        return "success";
    }
}
