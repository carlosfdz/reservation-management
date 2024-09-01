<?php

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Exports\EmployeeScheduleReportExport;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('reservations')->group(function () {
    Route::get('/employees/availability/interval', function () {
        return view('employee-interval-check');
    });

    Route::get('/employees/availability/check', function () {
        return view('employee-availability-check');
    });

    Route::get('/employees/export-schedule', function () {
        return Excel::download(new EmployeeScheduleReportExport, 'employee_schedule_report.xlsx');
    });
});
