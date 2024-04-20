<?php

namespace Dou\NovaPoshta\Responses;

use Dou\NovaPoshta\Contract\ResponseContract;

class BaseResponse implements ResponseContract
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

    public function isSuccess(): bool
    {
        return $this->data['success'] ?? false;
    }

    public function getItem($key = null, int $index = 0): mixed
    {
        if ($key) {
            return $this->data['data'][$index][$key] ?? null;
        }

        return $this->data['data'][$index] ?? null;
    }
}
