<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\Test;


use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToRandomQuestionDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Test\RandomQuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Test\RandomQuestionDocument();
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());
        $document->setIsDifferentSource($dto->getIsDifferentSource());
        $document->setNumberSubQuestion($dto->getNumberSubQuestion());
        $document->setReferId(uniqid('', true));
        $document->setMark($dto->getMark());
       
        return $document;
            
    }
}