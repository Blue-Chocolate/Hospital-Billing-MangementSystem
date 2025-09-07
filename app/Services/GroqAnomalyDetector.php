<?php 

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class GroqAnomalyDetector
{
    protected $client;
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = Config::get('services.groq.api_key');
        $this->endpoint = Config::get('services.groq.endpoint');
    }

    public function detectAnomaly($bill, $historicalBills)
    {
        $billData = [
            'id' => $bill->id,
            'amount' => $bill->amount,
            'department' => $bill->department->name,
            'bill_date' => $bill->bill_date->toDateString(),
        ];

        $departmentAverages = [];
        foreach ($historicalBills as $historicalBill) {
            $dept = $historicalBill->department->name;
            $departmentAverages[$dept][] = $historicalBill->amount;
        }

        $context = '';
        foreach ($departmentAverages as $dept => $amounts) {
            $avg = count($amounts) > 0 ? array_sum($amounts) / count($amounts) : 0;
            $context .= "Department $dept: Average bill amount = $$avg\n";
        }

        $prompt = <<<EOD
You are an AI anomaly detector for hospital bills. Given the following bill and historical data, determine if the bill is anomalous (e.g., unusually high amount, suspicious department). Return a JSON object with `is_anomaly` (boolean) and `reason` (string).

Bill:
- ID: {$billData['id']}
- Amount: \${$billData['amount']}
- Department: {$billData['department']}
- Date: {$billData['bill_date']}

Historical Data:
$context

Criteria for anomaly:
- Amount significantly higher than the department's average (e.g., >2x average).
- Suspicious patterns (e.g., same department with frequent high bills).

Response format:
```json
{
  "is_anomaly": boolean,
  "reason": "string"
}
```
EOD;

        try {
            $response = $this->client->post($this->endpoint, [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'mixtral-8x7b-32768',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                ],
            ]);

            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return json_encode([
                'is_anomaly' => false,
                'reason' => 'Error detecting anomaly: ' . $e->getMessage(),
            ]);
        }
    }
}