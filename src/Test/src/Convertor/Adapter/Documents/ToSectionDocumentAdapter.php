<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToSectionDocumentAdapter implements ConvertAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Test\SectionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Test\SectionDocument();
        $document->setName($dto->getName());
        $document->setDescription($dto->getDescription());
                
        $questions = $dto->getQuestions();
        foreach($questions as $question) {
            $questionDocument = $this->convertor->convertToDocument($question);
            $document->addQuestion($questionDocument);          
        }
        
        return $document;            
    }
}