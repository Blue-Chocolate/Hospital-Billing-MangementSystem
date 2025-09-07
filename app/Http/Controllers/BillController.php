<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;

class BillController extends Controller
{
    public function receipt(Bill $bill)
    {
        $pdf = Pdf::loadView('bills.receipt', compact('bill'));
        return $pdf->download('receipt-' . $bill->id . '.pdf');
    }
}
