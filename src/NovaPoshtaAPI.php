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
    private string $apiKey;

    /**
     * NovaPoshtaAPI constructor.
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Отправить запрос в API Новой Почты
     *
     * @param RequestContract $request
     *
     * @return ResponseContract
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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
