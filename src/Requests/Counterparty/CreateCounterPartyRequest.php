<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyResponse;
use Dou\NovaPoshta\Requests\BaseRequest;

class CreateCounterPartyRequest extends BaseRequest implements RequestContract
{
    private string $phone;
    private string $firstName;
    private string $lastName;
    private ?string $middleName;
    private ?string $email;

    public function setFields(string $phone, string $firstName, string $lastName, ?string $middleName = null, ?string $email = null)
    {
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->email = $email;
    }

    public function getRequest(): array
    {
        return [
            'modelName'        => 'Counterparty',
            'calledMethod'     => 'save',
            'methodProperties' => [
                'FirstName'            => $this->firstName,
                'MiddleName'           => $this->middleName,
                'LastName'             => $this->lastName,
                'Phone'                => $this->phone,
                'Email'                => $this->email,
                'CounterpartyType'     => 'PrivatePerson',
                'CounterpartyProperty' => 'Recipient',
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
