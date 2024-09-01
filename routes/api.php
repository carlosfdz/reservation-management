<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\EmployeeAvailabilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Get employees working in a given time interval.
 *
 * This endpoint returns a list of employees who are working within the specified time interval.
 *
 * URL: http://localhost:8000/api/employees/availability/interval
 * Method: GET
 * Query Parameters:
 * - start_date_time (string, required): The start date and time of the interval in 'YYYY-MM-DD HH:MM:SS' format.
 * - end_date_time (string, required): The end date and time of the interval in 'YYYY-MM-DD HH:MM:SS' format.
 *
 * Example:
 * http://localhost:8000/api/employees/availability/interval?start_date_time=2024-09-01%2009:00:00&end_date_time=2024-09-01%2010:00:00
 * or
 * http://localhost:8000/reservations/employee-availability
 */
Route::get('/employees/availability/interval', [EmployeeAvailabilityController::class, 'checkEmployeesInInterval']);

/**
 * Check availability of employees at a specific date and time.
 *
 * This endpoint returns a list of employees who are available at the specified date and time.
 *
 * URL: http://localhost:8000/api/employees/availability
 * Method: GET
 * Query Parameters:
 * - date_time (string, required): The date and time to check availability in 'YYYY-MM-DD HH:MM:SS' format.
 *
 * Example:
 * http://localhost:8000/api/employees/availability?date_time=2024-09-02%2009:00:00
 */
Route::get('employees/availability', [EmployeeAvailabilityController::class, 'checkAvailability']);
