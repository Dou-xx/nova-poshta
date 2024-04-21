<?php

namespace Dou\NovaPoshta\Contract;

interface RequestContract
{
    /**
     * Получить массив параметров запроса
     *
     * @return array
     */
    public function getRequest(): array;

    /**
     * Класс ответа
     *
     * @return ResponseContract
     */
    public function getResponseClass(): ResponseContract;

    /**
     * Отправить запрос в API Новой Почты
     *
     * @return ResponseContract
     */
    public function send(): ResponseContract;
}
