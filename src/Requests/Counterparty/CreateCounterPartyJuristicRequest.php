<?php

namespace Dou\NovaPoshta\Requests\Counterparty;

use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\CounterPartyResponse;
use Dou\NovaPoshta\Requests\BaseRequest;

class CreateCounterPartyJuristicRequest extends BaseRequest implements RequestContract
{
    private string $edrpou;

    public function setFields(string $edrpou)
    {
        $this->edrpou = $edrpou;
    }

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

    public function getResponseClass(): ResponseContract
    {
        return new CounterPartyResponse();
    }

    public function send(): CounterPartyResponse
    {
        return $this->run($this);
    }
}
