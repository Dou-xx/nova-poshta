<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyListResponse;
use Dou\NovaPoshta\Requests\BaseRequest;

class GetSenderRequest extends BaseRequest implements RequestContract
{
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

    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyListResponse();
    }

    public function send(): CounterPartyListResponse
    {
        return $this->run($this);
    }
}
