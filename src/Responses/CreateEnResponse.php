<?php

namespace Dou\NovaPoshta\Responses;

class CreateEnResponse extends BaseResponse
{
    /**
     * Получить ТТН
     *
     * @return null|string
     */
    public function getTTN(): ?string
    {
        return $this->getItem('IntDocNumber');
    }

    /**
     * Получить стоимость доставки
     *
     * @return null|string
     */
    public function getDeliveryCost(): ?string
    {
        return $this->getItem('CostOnSite');
    }

    /**
     * Получить ориентировочное время доставки посылки получателю
     *
     * @return null|string
     */
    public function getEstimatedDeliveryDate(): ?string
    {
        return $this->getItem('EstimatedDeliveryDate');
    }
}
