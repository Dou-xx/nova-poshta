<?php

namespace Dou\NovaPoshta\Contract;

interface ResponseContract
{
    /**
     * Заполнить полученными данными ответа Новой Почты
     *
     * @param array $data
     *
     * @return self
     */
    public function fill(array $data): self;
}
