<?php 


namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class GroqGeneralService
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

    public function explainFastLanguageModels()
    {
        $prompt = "Explain the importance of fast language models";

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
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true)['choices'][0]['message']['content'];
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}