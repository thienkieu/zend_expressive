<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromExamResultHasSectionTestDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\ExamResult\ExamResultHasSectionTestDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $test = $document->getTest();
        $dto = new \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO();
        
        $dto->setTitle($document->getTitle());
        $dto->setId($document->getId());
        $dto->setTime($document->getTime());
        $dto->setExamId($document->getExamId());
        $dto->setStartDate($document->getStartDate());
        
        $candiateDocument = $document->getCandidate();
        $candiateDTO = $this->convertor->convertToDTO($candiateDocument, $options);
        $dto->setCandidate($candiateDTO);

        $test = $this->convertor->convertToDTO($document->getTest(), $options);
        $dto->setTest($test);
        
        return $dto;
    }
    
}