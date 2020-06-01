<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToPlatformDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\PlatformDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dtoObject, $options = []) 
    {
        $document = new \Test\Documents\PlatformDocument();
        $document->setName($dtoObject->getName());
        
        return $document;            
    }
}