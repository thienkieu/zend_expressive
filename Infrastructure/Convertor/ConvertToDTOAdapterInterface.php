<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

interface ConvertToDTOAdapterInterface {
    public function isHandle($dtoObject, $toDTOName) : bool;
    public function convert($dtoObject);
}