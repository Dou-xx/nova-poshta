<?php

namespace Dou\NovaPoshta;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use GuzzleHttp\Client;

class NovaPoshtaAPI
{
    /**
     * API Ключ отправителя
     *
     * @var string
     */
    private string $apiKey; // 78f19724b096bb6d5f6a0c19b1541c0a

    /**
     * NovaPoshtaAPI constructor.
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(RequestContract $request): ResponseContract
    {
        try {
            $baseHeaders = [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ];

            $client = new Client([
                'base_uri' => 'https://api.novaposhta.ua/v2.0/json/',
                'headers'  => $baseHeaders,
            ]);

            $data = $request->getRequest();
            $data['apiKey'] = $this->apiKey;

            $response = $client->request('POST', '', [
                'json' => $data,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $request->getResponseClass()->fill($data);
        } catch (\Exception $exception) {
            $errMessage = $exception->getMessage();

            return $request->getResponseClass()->fill(['error_msg' => $errMessage]);
        }
    }
}
