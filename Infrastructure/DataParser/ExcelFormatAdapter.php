<?php

declare(strict_types=1);

namespace Infrastructure\DataParser;

class ExcelFormatAdapter implements FormatAdapterInterface
{
    public function buildFormat($data, $type, $options = []) {
        return $data;
    }    
}
