<?php

namespace App\Services\NovaPoshta\ApiRequests\EN;

use App\Services\NovaPoshta\ApiRequests\NovaPoshtaApiContract;
use Illuminate\Support\Arr;

class TrackingRequest implements NovaPoshtaApiContract
{
    /**
     * @var array|string[]
     */
    protected array $requestStructure = [
        'modelName'    => 'TrackingDocument',
        'calledMethod' => 'getStatusDocuments',
    ];

    /**
     * @var null
     */
    protected $senderPhone;

    /**
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
     * Set TTN
     *
     * @param array $ttns
     *
     * @return $this
     */
    public function setTTNs(array $ttns): self
    {
        $data = [];

        foreach ($ttns as $item) {
            $data[] = [
                'DocumentNumber' => $item,
                'Phone' => $this->senderPhone,
            ];
        }

        Arr::set($this->requestStructure, 'methodProperties.Documents', $data);

        return $this;
    }

    public function getRequest(): array
    {
        return $this->requestStructure;
    }
}
