<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromVerbalDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Question\VerbalQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\VerbalQuestionDTO();
        $content = \Infrastructure\CommonFunction::revertToHost($document->getContent());
        $dto->setContent($content);
        
        $dto->setId($document->getId());
        
        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());

        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());
        
        $dto->setMark($document->getMark());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
       
        return $dto;
    }
    
}