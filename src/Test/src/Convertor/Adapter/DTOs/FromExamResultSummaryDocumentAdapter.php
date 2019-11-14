<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromExamResultSummaryDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Exam\ExamResultSummaryDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Exam\ExamResultSummaryDTO();
        $dto->setName($document->getName());
        $dto->setMark(sprintf("%01.2f", $document->getMark()));
        $dto->setType($document->getType());
        $dto->setComments($document->getComments());
        $dto->setIsScored($document->getIsScored());
        $dto->setCandidateMark(sprintf("%01.2f", $document->getCandidateMark()));
        $dto->setIsToeic($document->getIsToeic());
        $dto->setToeicExpirationDate($document->getToeicExpirationDate());

        return $dto;
    }
    
}