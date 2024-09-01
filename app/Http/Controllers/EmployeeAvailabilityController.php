<?php

namespace App\Http\Controllers;

use App\Services\EmployeeAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeAvailabilityController extends Controller
{
    protected $employeeAvailabilityService;

    public function __construct(EmployeeAvailabilityService $employeeAvailabilityService)
    {
        $this->employeeAvailabilityService = $employeeAvailabilityService;
    }

    /**
     * Get employees working in a specific time interval.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkEmployeesInInterval(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'start_date_time' => 'required|date_format:Y-m-d H:i:s',
            'end_date_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $startDateTime = $validatedData['start_date_time'];
        $endDateTime = $validatedData['end_date_time'];

        $employees = $this->employeeAvailabilityService->getEmployeesWorkingInInterval($startDateTime, $endDateTime);

        return response()->json([
            'employees' => $employees,
        ]);
    }

    /**
     * Get available employees at a specific date and time.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'date_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $dateTimeNY = $validatedData['date_time'];
        // $dateTimeNY = '2024-09-04 09:00:00';

        $availableEmployees = $this->employeeAvailabilityService->getAvailableEmployees($dateTimeNY);

        return response()->json([
            'available_employees' => $availableEmployees,
        ]);
    }
}
