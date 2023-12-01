<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentController extends Controller
{
    public function index(): Collection
    {
        $departments = Department::query()->orderBy('name')->get();
        return $departments;
    }
}
