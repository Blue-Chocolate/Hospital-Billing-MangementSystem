<?php

namespace Tests\Unit\Services;

use App\Models\Bill;
use App\Services\BillExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillExportServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_a_pdf_response_with_current_month_bills()
    {
        // Arrange: Create bills for current month & previous month
        Bill::factory()->create(['bill_date' => now()]); // should be included
        Bill::factory()->create(['bill_date' => now()->subMonth()]); // should NOT be included

        $service = new BillExportService();

        // Act
        $response = $service->exportMonthlyReport();

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }
}
