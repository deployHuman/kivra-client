<?php

namespace DeployHuman\kivra\Dataclass;

class Invoice
{



    public function isValid(): bool
    {


        return true;
    }

    public function toArray(): array
    {
        return [];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
