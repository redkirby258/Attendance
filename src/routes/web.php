<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ApplicationController;

Route::middleware(['auth'])->group(function () {
    Route::get ('/attendance'            , [AttendanceController::class, 'showClockInForm'])->name('attendance.show');
    Route::post('/attendance/clock-in'   , [AttendanceController::class, 'clockIn'       ])->name('attendance.clockIn');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'    ])->name('attendance.breakStart');
    Route::post('/attendance/break-end'  , [AttendanceController::class, 'breakEnd'      ])->name('attendance.breakEnd');
    Route::post('/attendance/clock-out'  , [AttendanceController::class, 'clockOut'      ])->name('attendance.clockOut');   // 退勤
    Route::get('/attendance/list', [AttendanceController::class, 'attendanceList'])->name('attendance.list');
    Route::match(['get', 'put'], '/attendance/{id}', [AttendanceController::class, 'showOrUpdate'])
    ->name('attendance.show');
    Route::post('/attendance/{id}/request-edit', [AttendanceController::class, 'requestEdit'])->name('attendance.requestEdit');
    Route::get('/requests', [ApplicationController::class, 'index'])->name('requests.index');

});





Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Fortify の login POST を利用
    Route::get('login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::post('logout', function () {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        })->name('logout');
    });
});

require __DIR__.'/auth.php';
