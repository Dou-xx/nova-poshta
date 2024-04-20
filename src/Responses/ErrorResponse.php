<?php

namespace Dou\NovaPoshta\Responses;

use Dou\NovaPoshta\Contract\ResponseContract;

class ErrorResponse implements ResponseContract
{
    private array $data = [];

    public function fill(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
