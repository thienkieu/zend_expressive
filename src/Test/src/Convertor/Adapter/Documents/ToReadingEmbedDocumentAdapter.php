<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToReadingEmbedDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\ReadingQuestionDTO && isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }

        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Test\ReadingQuestionDocument();
        $content = $this->replaceHost($dto->getContent());
        $document->setContent($content);

        $document->setSource($dto->getSource());
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());
        $document->setReferId($dto->getId());
        
        $questions = $dto->getSubQuestions();

        foreach($questions as $question) {
            $questionDocument = $this->convertor->convertToDocument($question, $options);
            $document->addSubQuestion($questionDocument);            
        }
        
        return $document;
            
    }

    protected function replaceHost($content, $options = []) {
        $host = \Infrastructure\CommonFunction::getServerHost();

        $content = str_replace($host, \Config\AppConstant::HOST_REPLACE, $content);
        return $content;
    }
}