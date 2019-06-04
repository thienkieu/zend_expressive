<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToListeningDocumentAdapter implements ConvertAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\ListeningQuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Question\ListeningQuestionDocument();
        $document->setContent(json_encode($dto->getContent()));
        $document->setPath($dto->getPath());
        $document->setRepeat($dto->getRepeat());
        $document->setSource($dto->getSource());
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());

        $questions = $dto->getSubQuestions();

        foreach($questions as $question) {
            $questionDocument = $this->convertor->convertToDocument($question);
            $document->addSubQuestion($questionDocument);            
        }
        
        return $document;
            
    }
}