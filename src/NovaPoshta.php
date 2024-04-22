<?php

namespace Dou\NovaPoshta;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Requests\Address\CreateAddressRequest;
use Dou\NovaPoshta\Requests\Address\GetAddressByRefRequest;
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyContactRequest;
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyJuristicRequest;
use Dou\NovaPoshta\Requests\Counterparty\CreateCounterPartyRequest;
use Dou\NovaPoshta\Requests\Counterparty\GetCounterPartyContactsRequest;
use Dou\NovaPoshta\Requests\Counterparty\GetSenderRequest;
use Dou\NovaPoshta\Requests\EN\CreateExpressWaybillRequest;
use Dou\NovaPoshta\Requests\EN\TrackingRequest;

/**
 * @method CreateAddressRequest              CreateAddressRequest()
 * @method GetAddressByRefRequest            GetAddressByRefRequest()
 * @method CreateCounterPartyRequest         CreateCounterPartyRequest()
 * @method CreateCounterPartyContactRequest  CreateCounterPartyContactRequest()
 * @method CreateCounterPartyJuristicRequest CreateCounterPartyJuristicRequest()
 * @method GetCounterPartyContactsRequest    GetCounterPartyContactsRequest()
 * @method GetSenderRequest                  GetSenderRequest()
 * @method CreateExpressWaybillRequest       CreateExpressWaybillRequest()
 * @method TrackingRequest                   TrackingRequest()
 */
class NovaPoshta
{
    private array $register = [
        'CreateAddressRequest'              => CreateAddressRequest::class,
        'GetAddressByRefRequest'            => GetAddressByRefRequest::class,
        'CreateCounterPartyRequest'         => CreateCounterPartyRequest::class,
        'CreateCounterPartyContactRequest'  => CreateCounterPartyContactRequest::class,
        'CreateCounterPartyJuristicRequest' => CreateCounterPartyJuristicRequest::class,
        'GetCounterPartyContactsRequest'    => GetCounterPartyContactsRequest::class,
        'GetSenderRequest'                  => GetSenderRequest::class,
        'CreateExpressWaybillRequest'       => CreateExpressWaybillRequest::class,
        'TrackingRequest'                   => TrackingRequest::class,
    ];

    /**
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
     * @param string $method
     * @param array $arguments
     *
     * @return null|RequestContract
     * @throws \Exception
     *
     */
    public function __call(string $method, array $arguments)
    {
        if (ArrHelper::has($this->register, $method)) {
            return $this->make($method);
        }

        throw new \Exception("Class {$method} not found");
    }

    /**
     * @param string $name
     *
     * @return null|RequestContract
     */
    private function make(string $name): RequestContract|null
    {
        $className = $this->register[$name] ?? null;

        if (!$className) {
            return null;
        }

        return new $className($this->apiKey);
    }
}
