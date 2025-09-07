<?php

namespace App\Services;

use App\Models\Bill;
use App\Services\GroqAnomalyDetector;
use Filament\Notifications\Notification;

class BillAnomalyService
{
    public function __construct(private GroqAnomalyDetector $detector) {}

    /**
     * Detect anomaly for a bill using historical data of the same department.
     */
    public function checkAndNotify(Bill $bill): void
    {
        $historicalBills = Bill::where('department_id', $bill->department_id)
            ->where('id', '!=', $bill->id)
            ->get();

        $result = json_decode($this->detector->detectAnomaly($bill, $historicalBills), true);

        $bill->update(['is_anomaly' => $result['is_anomaly']]);

        Notification::make()
            ->title($result['is_anomaly'] ? 'Anomaly Detected' : 'No Anomaly')
            ->body($result['reason'])
            ->send();
    }
}
