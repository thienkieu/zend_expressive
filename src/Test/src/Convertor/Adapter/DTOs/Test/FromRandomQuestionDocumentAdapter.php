<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromRandomQuestionDocumentAdapter implements ConvertAdapterInterface {
    
    private $container;
    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isHandle($document) : bool
    {
        if ($document instanceof \Test\Documents\Test\RandomQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Test\RandomQuestionDTO();
        $dto->setType($document->getType());
        $dto->setSubType($document->getSubType());
        $dto->setNumberSubQuestion($document->getNumberSubQuestion());
        $dto->setIsDifferentSource($document->getIsDifferentSource());
                
        return $dto;
    }
    
}