<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

interface ConvertToDTOAdapterInterface {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool;
    public function convert($dtoObject);
}