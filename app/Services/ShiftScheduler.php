<?php 


namespace App\Services;

use App\Models\Shift;
use App\Notifications\ShiftAssignmentNotification;
use Carbon\Carbon;

class ShiftScheduler
{
    public function assignShift($employeeId, $startTime, $endTime)
    {
        $conflicts = Shift::where('employee_id', $employeeId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })->exists();

        if ($conflicts) {
            throw new \Exception('Shift conflict detected.');
        }

        $shift = Shift::create([
            'employee_id' => $employeeId,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $employee = $shift->employee;
        if ($employee->email) {
            $employee->notify(new ShiftAssignmentNotification($shift));
        }

        return $shift;
    }
}