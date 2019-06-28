<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToWritingDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\WritingQuestionDTO && !isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }

        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Question\WritingQuestionDocument();
        $document->setContent($dto->getContent());
        $document->setSource($dto->getSource());
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());
        
        return $document;
            
    }
}