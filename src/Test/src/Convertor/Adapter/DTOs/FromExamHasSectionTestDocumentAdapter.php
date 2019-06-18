<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromExamHasSectionTestDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Exam\ExamHasSectionTestDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Exam\ExamHasSectionTestDTO();
        $dto->setTitle($document->getTitle());
        $dto->setId($document->getId());
        $dto->setTime($document->getTime());
        $dto->setStartDate($document->getStartDate());
        
        $candiateDocuments = $document->getCandidates();
        $candiateDTOs = [];
        foreach($candiateDocuments as $candiate) {
            $candiateDTOs[] = $this->convertor->convertToDTO($candiate, $options);
        }
        $dto->setCandidates($candiateDTOs);

        $test = $this->convertor->convertToDTO($document->getTest(), $options);
        $dto->setTest($test);
        
        return $dto;
    }
    
}