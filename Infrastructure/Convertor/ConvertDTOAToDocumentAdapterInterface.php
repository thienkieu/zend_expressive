<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

interface ConvertDTOAToDocumentAdapterInterface {
    public function isHandleConvertDTOToDocument($object, $options = []) : bool;
    public function convert($dtoObject);
}