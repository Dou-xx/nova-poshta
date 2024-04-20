<?php

namespace Dou\NovaPoshta\Contract;

interface ResponseContract
{
    public function fill(array $data): self;
}
