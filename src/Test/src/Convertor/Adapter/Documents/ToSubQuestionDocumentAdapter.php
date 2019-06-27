<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToSubQuestionDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\SubQuestionDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dtoObject, $options = []) 
    {
        $document = new \Test\Documents\Question\SubQuestionDocument();        
        $document->setContent(json_encode($dtoObject->getContent()));
        $document->setOrder($dtoObject->getOrder());
        $document->setMark($dtoObject->getMark());
        
        $answers = $dtoObject->getAnswers();
        foreach($answers as $answer){
            $a = new \Test\Documents\Question\AnswerDocument();
            $a->setContent($answer->getContent());
            $a->setOrder($answer->getOrder());
            $document->addAnswer($a);
        }
        
        return $document;            
    }
}