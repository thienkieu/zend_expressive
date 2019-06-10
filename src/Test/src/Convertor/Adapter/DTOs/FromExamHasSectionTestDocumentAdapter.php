<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromExamHasSectionTestDocumentAdapter implements ConvertAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Exam\ExamHasSectionTestDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Exam\ExamHasSectionTestDTO();
        $dto->setTitle($document->getTitle());
        $dto->setId($document->getId());
        $dto->setTime($document->getTime());
        $dto->setStartDate($document->getStartDate());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $candiateDocuments = $document->getCandidates();
        $candiateDTOs = [];
        foreach($candiateDocuments as $candiate) {
            $candiateDTOs[] = $documentToDTOConvertor->convertToDTO($candiate);
        }
        $dto->setCandidates($candiateDTOs);

        $test = $documentToDTOConvertor->convertToDTO($document->getTest());
        $dto->setTest($test);
        
        return $dto;
    }
    
}