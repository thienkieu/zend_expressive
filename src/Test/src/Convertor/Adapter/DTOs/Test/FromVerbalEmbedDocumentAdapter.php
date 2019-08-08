<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromVerbalEmbedDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Test\VerbalQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\VerbalQuestionDTO();
        $dto->setContent($document->getContent());
        $dto->setId($document->getId());
        
        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());

        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());
        
        $dto->setAnswer($document->getAnswer());
        $dto->setCandidateMark($document->getCandidateMark());
        $dto->setMark($document->getMark());
        $dto->setComment($document->getComment());
        
        return $dto;
    }
    
}