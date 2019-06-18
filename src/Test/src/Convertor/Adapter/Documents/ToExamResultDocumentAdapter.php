<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToExamResultDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\ExamResult\ExamResultHasSectionTestDocument();
        $document->setExamId($dto->getExamId());
  
        $candidateDocument = $this->convertor->convertToDocument($dto->getCandidate());
        $document->setCandidate($candidateDocument); 

        $test = $this->convertor->convertToDocument($dto->getTest());
        $document->setTest($test);
        
        return $document;
            
    }
}