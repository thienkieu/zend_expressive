<?php

declare(strict_types=1);

namespace Infrastructure\DataParser;

interface FormatAdapterInterface
{
    public function buildFormat($data, $type, $options = []);
}
