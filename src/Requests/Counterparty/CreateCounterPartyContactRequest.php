<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyResponse;
use Dou\NovaPoshta\Requests\BaseRequest;

class CreateCounterPartyContactRequest extends BaseRequest implements RequestContract
{
    private string $ref;
    private string $phone;
    private string $firstName;
    private string $lastName;
    private ?string $middleName;

    public function setFields(string $counterpartyRef, string $phone, string $firstName, string $lastName, ?string $middleName = null)
    {
        $this->ref = $counterpartyRef;
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
    }

    public function getRequest(): array
    {
        return [
            'modelName'        => 'ContactPerson',
            'calledMethod'     => 'save',
            'methodProperties' => [
                'CounterpartyRef' => $this->ref,
                'FirstName'       => $this->firstName,
                'MiddleName'      => $this->middleName,
                'LastName'        => $this->lastName,
                'Phone'           => $this->phone,
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
