<?php

namespace Tests\Unit\Services;

use App\Models\Bill;
use App\Services\BillAnomalyService;
use App\Services\GroqAnomalyDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BillAnomalyServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_bill_as_anomaly_when_detector_returns_true()
    {
        // Arrange
        $bill = Bill::factory()->create(['is_anomaly' => false]);

        // Mock GroqAnomalyDetector
        $detectorMock = Mockery::mock(GroqAnomalyDetector::class);
        $detectorMock->shouldReceive('detectAnomaly')
            ->once()
            ->andReturn(json_encode(['is_anomaly' => true, 'reason' => 'Suspiciously high amount']));

        $service = new BillAnomalyService($detectorMock);

        // Act
        $service->checkAndNotify($bill);

        // Refresh model
        $bill->refresh();

        // Assert
        $this->assertTrue($bill->is_anomaly);
    }

    /** @test */
    public function it_updates_bill_as_not_anomaly_when_detector_returns_false()
    {
        // Arrange
        $bill = Bill::factory()->create(['is_anomaly' => true]);

        // Mock GroqAnomalyDetector
        $detectorMock = Mockery::mock(GroqAnomalyDetector::class);
        $detectorMock->shouldReceive('detectAnomaly')
            ->once()
            ->andReturn(json_encode(['is_anomaly' => false, 'reason' => 'Normal pattern']));

        $service = new BillAnomalyService($detectorMock);

        // Act
        $service->checkAndNotify($bill);

        $bill->refresh();

        // Assert
        $this->assertFalse($bill->is_anomaly);
    }
}
