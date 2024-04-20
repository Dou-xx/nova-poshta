<?php

namespace Dou\NovaPoshta\Responses;

class CounterPartyResponse extends BaseResponse
{
    public function getCounterPartyRef(): ?string
    {
        return $this->getItem('Ref');
    }

    public function getContactPersonRef(): ?string
    {
        $contactData = $this->getItem('ContactPerson');

        return $contactData['data'][0]['Ref'] ?? null;
    }
}
