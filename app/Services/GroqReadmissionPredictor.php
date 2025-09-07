<?php

namespace App\Services;

use App\Models\Patient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class GroqReadmissionPredictor
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

    public function predictReadmission(Patient $patient)
    {
        $medicalHistory = $patient->medical_history ? $patient->medical_history : 'None';
        $lastVisit = $patient->last_visit ? $patient->last_visit : 'Unknown';

        $prompt = <<<EOD
You are an AI predicting hospital patient readmission risks. Given the patient's medical history, predict the likelihood of readmission within 30 days. Return a JSON object with `risk` (string: "Low", "Medium", "High") and `reason` (string).

Patient:
- Name: {$patient->name}
- Medical History: {$medicalHistory}
- Last Visit: {$lastVisit}

Criteria:
- High risk: Chronic conditions like diabetes or hypertension with recent visits.
- Medium risk: Recent visits without chronic conditions.
- Low risk: No recent visits or minor conditions.

Response format:
{
  "risk": "string",
  "reason": "string"
}
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

            return json_decode($response->getBody()->getContents(), true)['choices'][0]['message']['content'];
        } catch (\Exception $e) {
            return json_encode([
                'risk' => 'Unknown',
                'reason' => 'Error predicting readmission: ' . $e->getMessage(),
            ]);
        }
    }
}