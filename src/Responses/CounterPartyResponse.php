<?php

namespace Dou\NovaPoshta\Responses;

class CounterPartyResponse extends BaseResponse
{
    /**
     * Получить Ref контрагента
     *
     * @return null|string
     */
    public function getCounterPartyRef(): ?string
    {
        return $this->getItem('Ref');
    }

    /**
     * Получить Ref первого контакта контрагента
     *
     * @return null|string
     */
    public function getContactPersonRef(): ?string
    {
        $contactData = $this->getItem('ContactPerson');

        return $contactData['data'][0]['Ref'] ?? null;
    }
}
