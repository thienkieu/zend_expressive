<?php

declare(strict_types=1);

namespace Infrastructure\Services\Interfaces;


interface CRMServiceInterface
{
    public function getEmployee($param, $options = []);
}
