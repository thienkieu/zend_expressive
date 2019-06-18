<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class DocumentToDTOConvertor implements DocumentToDTOConvertorInterface{
    
    /**
     * @var $adapters ConvertDocumentToDTOAdapterInterface[]
     */
    private  $adapters;
    private  $container;

    public function __construct($container, array $adapters = [])
    {
        $this->container = $container;
        $this->adapters = $adapters;
    }

    
    public function convertToDTO($document, $options = []) {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter($this->container, $this);
            if ($adapterInstance->isHandleConvertDocumentToDTO($document, $options)) {
                return $adapterInstance->convert($document, $options = []);
            }
        }

        return null;
    }
}