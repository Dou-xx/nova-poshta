<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\CounterPartyResponse;

class CreateCounterPartyContactRequest extends BaseRequest implements RequestContract
{
    /**
     * Ref контрагента
     *
     * @var string
     */
    private string $ref;

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
     * Заполнение данных
     *
     * @param string      $counterpartyRef
     * @param string      $phone
     * @param string      $firstName
     * @param string      $lastName
     * @param null|string $middleName
     *
     * @return self
     */
    public function setFields(string $counterpartyRef, string $phone, string $firstName, string $lastName, ?string $middleName = null): self
    {
        $this->ref = $counterpartyRef;
        $this->phone = $phone;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;

        return $this;
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
                'Phone'           => $this->clearPhone($this->phone),
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
