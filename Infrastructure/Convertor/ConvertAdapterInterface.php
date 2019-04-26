<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

interface ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool;
    public function convert($dtoObject);
}