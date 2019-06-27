<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToExamDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Exam\ExamHasSectionTestDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Exam\ExamHasSectionTestDocument();
        $document->setTitle($dto->getTitle());
        $document->setTime($dto->getTime());
        $document->setStartDate($dto->getStartDate());
        $document->setReferId($dto->getTest()->getId());

        $candidates = $dto->getCandidates();
        foreach($candidates as $candidate) {
            $candidateDocument = $this->convertor->convertToDocument($candidate, $options);
            $document->addCandidate($candidateDocument);            
        }

        $test = $this->convertor->convertToDocument($dto->getTest(), $options);
        $document->setTest($test);
        
        return $document;
            
    }
}