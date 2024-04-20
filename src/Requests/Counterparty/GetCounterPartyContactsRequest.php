<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyListResponse;
use Dou\NovaPoshta\Requests\BaseRequest;

class GetCounterPartyContactsRequest extends BaseRequest implements RequestContract
{
    private string $ref;

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

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

    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyListResponse();
    }

    public function send(): CounterPartyListResponse
    {
        return $this->run($this);
    }
}
