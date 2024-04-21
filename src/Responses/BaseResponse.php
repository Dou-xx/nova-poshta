<?php

namespace Dou\NovaPoshta\Responses;

use Dou\NovaPoshta\Contract\ResponseContract;

class BaseResponse implements ResponseContract
{
    /**
     * Данные ответа API Новой Почты
     *
     * @var array
     */
    private array $data = [];

    /**
     * {@inheritDoc}
     */
    public function fill(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Получить весь массив ответа API
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Успех запроса
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->data['success'] ?? false;
    }

    /**
     * Получить Ref первого элемента ответа API
     *
     * @return null|string
     */
    public function getRef(): ?string
    {
        return $this->getItem('Ref');
    }

    /**
     * Получить элемент ответа или его поле
     *
     * @example getItem('FirstName') | getItem('Ref', 1) | getItem(null, 1)
     *
     * @param $key
     * @param int $index
     *
     * @return mixed
     */
    public function getItem($key = null, int $index = 0): mixed
    {
        if ($key) {
            return $this->data['data'][$index][$key] ?? null;
        }

        return $this->data['data'][$index] ?? null;
    }

    /**
     * Получить массив ошибок из ответа API
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->data['errors'] ?? [];
    }

    /**
     * Массив кодов ошибок
     *
     * @return array
     */
    public function getErrorCodes(): array
    {
        return $this->data['errorCodes'] ?? [];
    }

    /**
     * Получить массив предупреждений из ответа API
     *
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->data['warnings'] ?? [];
    }

    /**
     * Получить массив кодов предупреждений из ответа API
     *
     * @return array
     */
    public function getWarningCodes(): array
    {
        return $this->data['warningCodes'] ?? [];
    }
}
