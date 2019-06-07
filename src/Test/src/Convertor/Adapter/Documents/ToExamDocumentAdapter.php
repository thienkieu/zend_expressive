<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToExamDocumentAdapter implements ConvertAdapterInterface {
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
    
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Exam\ExamHasSectionTestDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Exam\ExamHasSectionTestDocument();
        $document->setTitle($dto->getTitle());
        $document->setTime($dto->getTime());
        $document->setStartDate($dto->getStartDate());
                
        $candidates = $dto->getCandidates();
        foreach($candidates as $candidate) {
            $candidateDocument = $this->convertor->convertToDocument($candidate);
            $document->addCandidate($candidateDocument);            
        }

        $test = $this->convertor->convertToDocument($dto->getTest());
        $document->setTest($test);
        
        return $document;
            
    }
}