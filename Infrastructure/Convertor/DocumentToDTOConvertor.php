<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class DocumentToDTOConvertor implements DocumentToDTOConvertorInterface{
    
    /**
     * @var $adapters ConvertAdapterInterface[]
     */
    private  $adapters;
    private  $container;

    public function __construct($container, array $adapters = [])
    {
        $this->container = $container;
        $this->adapters = $adapters;
    }

    
    public function convertToDTO($document) {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter($this->container);
            if ($adapterInstance->isHandle($document)) {
                return $adapterInstance->convert($document);
            }
        }

        return null;
    }
}