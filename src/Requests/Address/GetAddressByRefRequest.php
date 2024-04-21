<?php

namespace Dou\NovaPoshta\Requests\Address;

use Dou\NovaPoshta\Requests\BaseRequest;
use Dou\NovaPoshta\Contract\RequestContract;
use Dou\NovaPoshta\Contract\ResponseContract;
use Dou\NovaPoshta\Responses\BaseResponse;

class GetAddressByRefRequest extends BaseRequest implements RequestContract
{
    /**
     * Ref отделения
     *
     * @var string
     */
    private string $ref;

    /**
     * Установить Ref адреса
     *
     * @param string $ref
     *
     * @return GetAddressByRefRequest
     */
    public function setRef(string $ref): self
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return [
            'modelName'        => 'Address',
            'calledMethod'     => 'getWarehouses',
            'methodProperties' => [
                'Ref' => $this->ref,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getResponseClass(): ResponseContract
    {
        return new BaseResponse();
    }

    /**
     * @inheritDoc
     */
    public function send(): ResponseContract|BaseResponse
    {
        return $this->run($this);
    }
}
