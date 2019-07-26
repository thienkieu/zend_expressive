<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromNonSubEmbedDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Test\NonSubQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\NonSubQuestionDTO();
        $content = \Infrastructure\CommonFunction::revertToHost($document->getContent());
        $dto->setContent($content);

        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());
        
        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());
        
        $dto->setId($document->getId());
        $dto->setCandidateMark($document->getCandidateMark());
        $dto->setMark($document->getMark());
        $dto->setNumberCorrectSubQuestion($document->getNumberCorrectSubQuestion());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $answersDocuments = $document->getAnswers();
        $answers = [];
        foreach($answersDocuments as $a) {
            $answers[] = $documentToDTOConvertor->convertToDTO($a, $options);
        }
        $dto->setAnswers($answers);    
        
        return $dto;
    }
}