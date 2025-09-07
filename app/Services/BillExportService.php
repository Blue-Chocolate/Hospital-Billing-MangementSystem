<?php

namespace App\Services;

use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class BillExportService
{
    /**
     * Export bills for the current month as a downloadable PDF.
     */
    public function exportMonthlyReport()
    {
        $bills = Bill::whereMonth('bill_date', now()->month)->get();

        $pdf = Pdf::loadView('bills.monthly_report', ['bills' => $bills]);

        return Response::streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'monthly-report-' . now()->format('Y-m') . '.pdf');
    }
}
