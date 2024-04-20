<?php

namespace Dou\NovaPoshta\Responses;

class CreateEnResponse extends BaseResponse
{
    public function getRef(): ?string
    {
        return $this->getItem('Ref');
    }

    public function getTTN(): ?string
    {
        return $this->getItem('IntDocNumber');
    }

    public function getDeliveryCost():?string
    {
        return $this->getItem('CostOnSite');
    }

    public function getEstimatedDeliveryDate():?string
    {
        return $this->getItem('EstimatedDeliveryDate');
    }
}
