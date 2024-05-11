<?php

namespace Dou\NovaPoshta\Requests\Address;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\BaseResponse;

class CreateAddressRequest extends BaseRequest implements RequestContract
{
    /**
     * Ref контрагента
     *
     * @var string
     */
    private string $counterpartyRef;

    /**
     * Ref улицы из справочника улиц Новой Почты
     *
     * @var string
     */
    private string $streetRef;

    /**
     * Номер дома
     *
     * @var null|string
     */
    private ?string $buildNumber;

    /**
     * Номер квартиры
     *
     * @var null|string
     */
    private ?string $flat;

    /**
     * Комментарий, можно указать уточнение адреса, такое как название магазина
     *
     * @var string|null
     */
    private ?string $note;

    /**
     * Заполнить данные для создания адреса
     *
     * @param string $counterpartyRef
     * @param string $streetRef
     * @param null|string $buildNumber
     * @param null|string $flat
     * @param null $note
     *
     * @return self
     */
    public function setFields(string $counterpartyRef, string $streetRef, ?string $buildNumber, ?string $flat, $note = null): self
    {
        $this->counterpartyRef = $counterpartyRef;
        $this->streetRef = $streetRef;
        $this->buildNumber = $buildNumber;
        $this->flat = $flat;
        $this->note = $note;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
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
                'Note'            => $this->note,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getResponseClass(): ResponseContract
    {
        return new BaseResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function send(): BaseResponse|ResponseContract
    {
        return $this->run($this);
    }
}
