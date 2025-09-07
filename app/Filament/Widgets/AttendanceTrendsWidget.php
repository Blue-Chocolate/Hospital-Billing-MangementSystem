<?Php 

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class AttendanceTrendsWidget extends ChartWidget
{
    protected static ?string $heading = 'Employee Attendance Trends';

    protected function getData(): array
    {
        $attendances = Attendance::whereMonth('date', now()->month)
            ->get()
            ->groupBy('date')
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'datasets' => [
                [
                    'label' => 'Attendance Count',
                    'data' => $attendances->values()->toArray(),
                    'backgroundColor' => '#36A2EB',
                ],
            ],
            'labels' => $attendances->keys()->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}