<?php

declare(strict_types=1);

namespace Infrastructure\Interfaces;


interface HandlerInterface
{
    public function isHandler($param, $options = []);
}
