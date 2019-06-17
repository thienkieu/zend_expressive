<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\Test;


use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Convertor\Adapter\Documents\ToReadingDocumentAdapter;
use Test\Convertor\Adapter\Documents\ToWritingDocumentAdapter;
use Test\Convertor\Adapter\Documents\ToListeningDocumentAdapter;


class ToQuestionDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Test\QuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {
        $document = new \Test\Documents\Test\QuestionDocument();
        $document->setGenerateFrom($dto->getGenerateFrom());
       
        $questionInfo = $this->convertor->convertToDocument($dto->getQuestionInfo());                
        $document->setQuestionInfo($questionInfo);
       
        return $document;            
    }
}