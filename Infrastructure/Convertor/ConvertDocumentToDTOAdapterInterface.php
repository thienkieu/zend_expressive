<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

interface ConvertDocumentToDTOAdapterInterface {
    public function isHandleConvertDocumentToDTO($object, $options = []) : bool;
    public function convert($dtoObject);
}