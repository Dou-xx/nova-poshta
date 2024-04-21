<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\CounterPartyListResponse;

class GetCounterPartyContactsRequest extends BaseRequest implements RequestContract
{
    /**
     * Ref контрагента
     *
     * @var string
     */
    private string $ref;

    /**
     * Установить Ref контрагента
     *
     * @param string $ref
     *
     * @return $this
     */
    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest(): array
    {
        return [
            'modelName'        => 'Counterparty',
            'calledMethod'     => 'getCounterpartyContactPersons',
            'methodProperties' => [
                'Ref' => $this->ref,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyListResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function send(): CounterPartyListResponse|ResponseContract
    {
        return $this->run($this);
    }
}
