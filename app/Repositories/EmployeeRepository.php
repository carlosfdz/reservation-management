<?php

namespace App\Repositories;

use App\Models\Employee;

class EmployeeRepository
{
    /**
     * Retrieve all employees.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Employee::all();
    }

    /**
     * Find an employee by ID.
     *
     * @param int $id
     * @return Employee|null
     */
    public function find(int $id): ?Employee
    {
        return Employee::find($id);
    }

    /**
     * Create a new employee.
     *
     * @param array $data
     * @return Employee
     */
    public function create(array $data): Employee
    {
        return Employee::create($data);
    }

    /**
     * Update an existing employee.
     *
     * @param Employee $employee
     * @param array $data
     * @return bool
     */
    public function update(Employee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    /**
     * Delete an employee.
     *
     * @param Employee $employee
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Employee $employee): ?bool
    {
        return $employee->delete();
    }
}
