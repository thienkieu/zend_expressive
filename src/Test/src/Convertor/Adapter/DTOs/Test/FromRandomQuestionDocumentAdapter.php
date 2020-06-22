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
        $typeDTO = $document->getType()->getParentType();
        if ($typeDTO) {
            $dto->setType($typeDTO->getName());
        }        
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());
        $dto->setRenderType($document->getType()->getRenderName());
        $dto->setPlatform($document->getPlatform()->getId());

        $dto->setNumberSubQuestion($document->getNumberSubQuestion());
        $dto->setIsDifferentSource($document->getIsDifferentSource());
        $dto->setIsKeepQuestionOrder(!!$document->getIsKeepQuestionOrder());
        $dto->setIsRandomAnswer(!!$document->getIsRandomAnswer());        
        $dto->setMark($document->getMark());
        return $dto;
    }
    
}