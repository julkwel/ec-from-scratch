<?php

namespace App\Manager;

use App\Services\EntityServices;

abstract class AbstractManager
{
    protected $entityServices;

    public function __construct(EntityServices $entityServices)
    {
        $this->entityServices = $entityServices;
    }
}
