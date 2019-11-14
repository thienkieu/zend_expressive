<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromSectionTestDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
    private $container;
    private $convertor;
    /**
     * Class constructor.
     */
    public function __construct($container, $convertor)
    {
        $this->container = $container;
        $this->convertor = $convertor;
    }

    public function isHandleConvertDocumentToDTO($document, $options = []) : bool
    {
        if ($document instanceof \Test\Documents\Test\SectionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Test\SectionDTO();
        $dto->setName($document->getName());
        $dto->setDescription($document->getDescription());
        $dto->setId($document->getId());
        $dto->setMark($document->getMark());
        $dto->setCandidateMark($document->getCandidateMark());
        $dto->setComment($document->getComment());
        $dto->setIsToeic($document->getIsToeic());
        $dto->setToeicExpirationDate($document->getToeicExpirationDate());
        
        $questionDocuments = $document->getQuestions();
        $questionDTOs = [];
        foreach($questionDocuments as $question) {
            $questionDTOs[] = $this->convertor->convertToDTO($question, $options);
        }
        $dto->setQuestions($questionDTOs);
        return $dto;
    }
    
}