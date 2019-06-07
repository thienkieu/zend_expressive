<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToSourceDocumentAdapter implements ConvertAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\SourceDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dtoObject) 
    {
        $document = new \Test\Documents\Question\SourceDocument();
        $document->setName($dtoObject->getName());
        
        return $document;            
    }
}