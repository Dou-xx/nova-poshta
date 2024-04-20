<?php

namespace Dou\NovaPoshta\Requests\Address;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyResponse;
use Dou\NovaPoshta\Requests\BaseRequest;

class CreateAddressRequest extends BaseRequest implements RequestContract
{
    private string $counterpartyRef;
    private string $streetRef;
    private ?string $buildNumber;
    private ?string $flat;

    public function setFields(string $counterpartyRef, string $streetRef, ?string $buildNumber, ?string $flat)
    {
        $this->counterpartyRef = $counterpartyRef;
        $this->streetRef = $streetRef;
        $this->buildNumber = $buildNumber;
        $this->flat = $flat;
    }

    public function getRequest(): array
    {
        return [
            'modelName'        => 'Address',
            'calledMethod'     => 'save',
            'methodProperties' => [
                'CounterpartyRef' => $this->counterpartyRef,
                'StreetRef'       => $this->streetRef,
                'BuildingNumber'  => $this->buildNumber,
                'Flat'            => $this->flat,
                'Note'            => null,
            ],
        ];
    }

    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyResponse();
    }

    public function send(): CounterPartyResponse
    {
        return $this->run($this);
    }
}
