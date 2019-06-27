<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromReadingEmbedDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Test\ReadingQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\ReadingQuestionDTO();
        $dto->setContent($document->getContent());
        $dto->setSubType($document->getSubType());
        $dto->setType($document->getType());
        $dto->setId($document->getId());
        $dto->setCandidateMark($document->getCandidateMark());
        $dto->setMark($document->getMark());
        $dto->setNumberCorrectSubQuestion($document->getNumberCorrectSubQuestion());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionDocuments = $document->getSubQuestions();
        $questions = [];
        foreach($questionDocuments as $q) {
            $questions[] = $documentToDTOConvertor->convertToDTO($q, $options);
        }
        $dto->setSubQuestions($questions);       
        
        return $dto;
    }
    
}