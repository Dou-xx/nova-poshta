<?php

namespace Dou\NovaPoshta\Requests\EN;

use Dou\NovaPoshta\ArrHelper;
use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Responses\BaseResponse;

class TrackingRequest extends BaseRequest implements RequestContract
{
    /**
     * @var array|string[]
     */
    private array $requestStructure = [
        'modelName'    => 'TrackingDocument',
        'calledMethod' => 'getStatusDocuments',
    ];

    /**
     * Номер телефона отправителя
     *
     * @var null|string
     */
    private string|null $senderPhone = null;

    /**
     * Заменить номер телефона отправителя
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setSenderPhone(string $phone): self
    {
        $this->senderPhone = $phone;

        return $this;
    }

    /**
     * Передать список TTN
     *
     * @param array $ttnList
     *
     * @return $this
     */
    public function setTTNs(array $ttnList): self
    {
        $data = [];

        foreach ($ttnList as $item) {
            $data[] = [
                'DocumentNumber' => $item,
                'Phone'          => $this->senderPhone,
            ];
        }

        ArrHelper::set($this->requestStructure, 'methodProperties.Documents', $data);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest(): array
    {
        return $this->requestStructure;
    }

    /**
     * {@inheritDoc}
     */
    public function getResponseClass(): ResponseContract
    {
        return new BaseResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function send(): ResponseContract|BaseResponse
    {
        return $this->run($this);
    }
}
