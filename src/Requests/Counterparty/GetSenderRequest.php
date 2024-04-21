<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\CounterPartyListResponse;

class GetSenderRequest extends BaseRequest implements RequestContract
{
    /**
     * {@inheritDoc}
     */
    public function getRequest(): array
    {
        return [
            'modelName'        => 'Counterparty',
            'calledMethod'     => 'getCounterparties',
            'methodProperties' => [
                'CounterpartyProperty' => 'Sender',
                // 'Page'                 => '1',
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
