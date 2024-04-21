<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\CounterPartyResponse;

class CreateCounterPartyJuristicRequest extends BaseRequest implements RequestContract
{
    /**
     * Код ЄДРПОУ
     *
     * @var string
     */
    private string $edrpou;

    /**
     * @param string $edrpou
     *
     * @return $this
     */
    public function setFields(string $edrpou): self
    {
        $this->edrpou = $edrpou;

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
                'EDRPOU'               => $this->edrpou,
                'CounterpartyType'     => 'Organization',
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
