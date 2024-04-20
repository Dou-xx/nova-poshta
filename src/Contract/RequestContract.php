<?php

namespace Dou\NovaPoshta\Contract;

interface RequestContract
{
    public function getRequest(): array;

    public function getResponseClass(): ResponseContract;
}