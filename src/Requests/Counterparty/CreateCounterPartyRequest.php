<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\CounterPartyResponse;

class CreateCounterPartyRequest extends BaseRequest implements RequestContract
{
    /**
     * Номер телефона
     *
     * @var string
     */
    private string $phone;

    /**
     * Имя
     *
     * @var string
     */
    private string $firstName;

    /**
     * Фамилия
     *
     * @var string
     */
    private string $lastName;

    /**
     * Отчество
     *
     * @var null|string
     */
    private ?string $middleName;

    /**
     * Email
     *
     * @var null|string
     */
    private ?string $email;

    /**
     * Заполнение данных
     *
     * @param string      $phone
     * @param string      $firstName
     * @param string      $lastName
     * @param null|string $middleName
     * @param null|string $email
     *
     * @return self
     */
    public function setFields(string $phone, string $firstName, string $lastName, ?string $middleName = null, ?string $email = null): self
    {
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest(): array
    {
        return [
            'modelName'        => 'Counterparty',
            'calledMethod'     => 'save',
            'methodProperties' => [
                'FirstName'            => $this->firstName,
                'MiddleName'           => $this->middleName,
                'LastName'             => $this->lastName,
                'Phone'                => $this->clearPhone($this->phone),
                'Email'                => $this->email,
                'CounterpartyType'     => 'PrivatePerson',
                'CounterpartyProperty' => 'Recipient',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function send(): CounterPartyResponse|ResponseContract
    {
        return $this->run($this);
    }
}
