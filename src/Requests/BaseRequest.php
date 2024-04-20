<?php

namespace Dou\NovaPoshta\Requests;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\NovaPoshtaAPI;
use Dou\NovaPoshta\Contract\ResponseContract;

class BaseRequest
{
    private string $apiKey;

    public function __construct(string $apiKey = '')
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    protected function run(RequestContract $requestContract): ResponseContract
    {
        $api = new NovaPoshtaAPI($this->apiKey);

        return $api->send($requestContract);
    }
}
