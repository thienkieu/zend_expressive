<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToListeningDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\ListeningQuestionDTO && !isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
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
            $questionDocument = $this->convertor->convertToDocument($question, $options);
            $document->addSubQuestion($questionDocument);            
        }
        
        return $document;
            
    }
}