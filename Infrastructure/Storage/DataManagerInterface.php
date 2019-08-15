<?php

declare(strict_types=1);

namespace Infrastructure\DataManagerInterface;


interface DataManagerInterface
{
    public function isHandler($param, $options = []);
}
