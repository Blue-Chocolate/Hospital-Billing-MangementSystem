<?php 


namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class PatientAdmissionsWidget extends ChartWidget
{
    protected static ?string $heading = 'Patient Admissions';

    protected function getData(): array
    {
        $appointments = Appointment::whereMonth('appointment_time', now()->month)
            ->get()
            ->groupBy(function ($appointment) {
                return \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'datasets' => [
                [
                    'label' => 'Admissions',
                    'data' => $appointments->values()->toArray(),
                    'backgroundColor' => '#FF6384',
                ],
            ],
            'labels' => $appointments->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}