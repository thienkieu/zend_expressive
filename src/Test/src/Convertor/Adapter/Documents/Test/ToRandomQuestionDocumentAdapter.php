<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\Test;


use Infrastructure\Convertor\ConvertAdapterInterface;

class ToRandomQuestionDocumentAdapter implements ConvertAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Test\RandomQuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Test\RandomQuestionDocument();
        $document->setType($dto->getType());
        $document->setSubType($dto->getSubType());
        $document->setIsDifferentSource($dto->getIsDifferentSource());
        $document->setNumberSubQuestion($dto->getNumberSubQuestion());
        
        return $document;
            
    }
}