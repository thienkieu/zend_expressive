<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToReadingDocumentAdapter implements ConvertAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\ReadingQuestionDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Question\ReadingQuestionDocument();
        $document->setContent(json_encode($dto->getContent()));
        $document->setSource($dto->getSource());
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());
        
        $questions = $dto->getQuestions();

        foreach($questions as $question) {
            $questionDocument = $this->convertor->convertToDocument($question);
            $document->addQuestion($questionDocument);            
        }
        
        return $document;
            
    }
}