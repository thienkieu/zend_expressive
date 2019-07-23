<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromRandomQuestionDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Test\RandomQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Test\RandomQuestionDTO();
        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());

        $dto->setNumberSubQuestion($document->getNumberSubQuestion());
        $dto->setIsDifferentSource($document->getIsDifferentSource());
        $dto->setMark($document->getMark());
                
        return $dto;
    }
    
}