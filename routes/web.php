<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AttendanceCorrectionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\MfaController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    Route::get('/mfa/challenge', [MfaController::class, 'challengeForm'])->name('mfa.challenge.form');
    Route::post('/mfa/challenge', [MfaController::class, 'challenge'])->name('mfa.challenge');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('success', 'Email berhasil diverifikasi.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Tautan verifikasi telah dikirim.');
    })->middleware('throttle:6,1')->name('verification.send');
    Route::get('/security/mfa/setup', [MfaController::class, 'setup'])->name('mfa.setup');
    Route::post('/security/mfa/enable', [MfaController::class, 'enable'])->name('mfa.enable');
    Route::get('/', function () {
        $user = auth()->user();
        if (in_array($user->role, ['super_admin', 'admin', 'hr_admin', 'manager'])) {
            return redirect()->route('dashboard.admin');
        }
        return redirect()->route('dashboard.employee');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('dashboard.admin');

    Route::get('/dashboard/employee', [DashboardController::class, 'employeeDashboard'])
        ->name('dashboard.employee');

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/attachment', [PermissionController::class, 'attachment'])
        ->name('permissions.attachment');
    Route::post('/permissions/{permission}/approve', [PermissionController::class, 'approve'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('permissions.approve');
    Route::post('/permissions/{permission}/reject', [PermissionController::class, 'reject'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('permissions.reject');

    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::post('/leaves/{leave}/approve', [LeaveController::class, 'approve'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [LeaveController::class, 'reject'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('leaves.reject');

    Route::get('/corrections', [AttendanceCorrectionController::class, 'index'])->name('corrections.index');
    Route::get('/corrections/create', [AttendanceCorrectionController::class, 'create'])->name('corrections.create');
    Route::post('/corrections', [AttendanceCorrectionController::class, 'store'])->name('corrections.store');
    Route::post('/corrections/{correction}/approve', [AttendanceCorrectionController::class, 'approve'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('corrections.approve');
    Route::post('/corrections/{correction}/reject', [AttendanceCorrectionController::class, 'reject'])
        ->middleware('role:admin,hr_admin,manager')
        ->name('corrections.reject');

    Route::middleware('role:admin,hr_admin,manager')->group(function () {
        Route::get('/attendance/monitoring', [AttendanceController::class, 'monitoring'])->name('attendance.monitoring');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');
    });

    Route::middleware('role:admin,hr_admin')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('departments', DepartmentController::class)->except(['create', 'edit', 'show']);
        Route::resource('positions', PositionController::class)->except(['create', 'edit', 'show']);

        Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
        Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
        Route::put('/shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
        Route::post('/shifts/assign', [ShiftController::class, 'assign'])->name('shifts.assign');

        Route::get('/schedules', [WorkScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules', [WorkScheduleController::class, 'update'])->name('schedules.update');
    });
});
