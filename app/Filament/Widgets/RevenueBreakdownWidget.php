<?php 


namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Department;
use Filament\Widgets\ChartWidget;

class RevenueBreakdownWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Department';

    protected function getData(): array
    {
        $departments = Department::with('bills')->get();
        $labels = $departments->pluck('name')->toArray();
        $data = $departments->map(function ($department) {
            return $department->bills->sum('amount');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data,
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}