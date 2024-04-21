<?php

namespace Dou\NovaPoshta\Requests;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\NovaPoshtaAPI;

class BaseRequest
{
    /**
     * API KEY Новой Почты
     *
     * @var string
     */
    private string $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Запрос в API Новой Почты
     *
     * @param RequestContract $requestContract
     *
     * @return ResponseContract
     */
    protected function run(RequestContract $requestContract): ResponseContract
    {
        $api = new NovaPoshtaAPI($this->apiKey);

        return $api->send($requestContract);
    }

    /**
     * @param string $phone
     *
     * @return string
     */
    protected function clearPhone(string $phone): string
    {
        return str_replace(['+', '(', ')', '-', ' '], '', $phone);
    }
}
