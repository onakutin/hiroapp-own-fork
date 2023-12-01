<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load(['notifications', 'position.department']);
});

Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
Route::get('/positions/all', [PositionController::class, 'getAllPositions'])->name('positions.all');
Route::get('/positions-dep/{department}', [PositionController::class, 'getPositionsByDepartment'])->name('positions.by_department');
Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
Route::get('/positions/{position}', [PositionController::class, 'show'])->whereNumber('position')->name('positions.show');
Route::post('/positions/{position}/edit', [PositionController::class, 'update'])->whereNumber('position')->name('positions.update');
Route::post('/positions/{position}/delete', [PositionController::class, 'destroy'])->whereNumber('position')->name('positions.destroy');

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}', [UserController::class, 'show'])->whereNumber('user')->name('users.show');
Route::post('/users/{user}/edit', [UserController::class, 'update'])->whereNumber('user')->name('users.update');
Route::post('/users/{user}/delete', [UserController::class, 'destroy'])->whereNumber('user')->name('users.destroy');

Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
Route::get('/applications/{application}', [ApplicationController::class, 'show'])->whereNumber('application')->name('applications.show');
Route::post('/applications/{application}/end', [ApplicationController::class, 'end'])->whereNumber('application')->name('applications.end');
Route::post('/applications/{application}/move', [ApplicationController::class, 'move'])->whereNumber('application')->name('applications.move');
Route::post('/applications/{application}/delete', [ApplicationController::class, 'destroy'])->whereNumber('application')->name('applications.destroy');
Route::post('/applications/notify', [ApplicationController::class, 'notify'])->name('applications.notify');

Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');

Route::post('/notify', [NotificationController::class, 'send'])->name('notifications.send');
Route::get('/notify/get', [NotificationController::class, 'get_unread_count'])->name('notifications.getunreadcount');
Route::post('/notify/markasread', [NotificationController::class, 'mark_as_read'])->name('notifications.markasread');

Route::get('/dashboard/count', [DashboardController::class, 'getTotalNumberOfApplications'])->name('dashboard.totalapplicants');
Route::get('/dashboard/data', [DashboardController::class, 'getTotalApplicantsDetail'])->name('dashboard.totalapplicantsdetail');
