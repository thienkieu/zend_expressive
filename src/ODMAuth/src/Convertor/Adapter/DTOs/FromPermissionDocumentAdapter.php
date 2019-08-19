<?php 

declare(strict_types=1);

namespace ODMAuth\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromPermissionDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    private $container;
    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isHandleConvertDocumentToDTO($document, $options = []) : bool
    {
        if ($document instanceof \ODMAuth\Documents\PermissionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \ODMAuth\DTOs\PermissionDTO();
        $dto->setName($document->getBusinessName());
        $dto->setAccessFunctions($document->getCodeFunctions());;
        $dto->setId($document->getId());
        
        return $dto;
    }
}