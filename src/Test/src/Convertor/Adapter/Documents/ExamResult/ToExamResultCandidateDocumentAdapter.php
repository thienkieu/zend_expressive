<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\ExamResult;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToExamResultCandidateDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
    protected $container;
    protected $convertor;

    /**
     * Class constructor.
     */
    public function __construct($container, $convertor)
    {
        $this->container = $container;
        $this->convertor = $convertor;
    }
    
    public function isHandleConvertDTOToDocument($dtoObject, $options = []) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Exam\CandidateDTO && isset($options[\Config\AppConstant::ToDocumentClass]) && $options[\Config\AppConstant::ToDocumentClass] === \Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\ExamResult\CandidateDocument();
        $document->setName($dto->getName());
        $document->setEmail($dto->getEmail());
        $document->setObjectId($dto->getObjectId());
        $document->setType($dto->getType());
        $document->setId($dto->getId());

        return $document;
    }
}