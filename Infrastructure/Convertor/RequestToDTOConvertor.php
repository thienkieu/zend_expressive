<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class RequestToDTOConvertor implements RequestToDTOConvertorInterface {
    protected $container;
    
    /**
     * @var $adapters ConvertToDTOAdapterInterface[]
     */
    private  $adapters;

    public function __construct($container, array $adapters = [])
    {
        $this->container = $container;
        $this->adapters = $adapters;
    }

    
    public function convertToDTO($jsonData, $toDTOName) {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter($this->container, $this);
            if ($adapterInstance->isHandleConvertToDTO($jsonData, $toDTOName)) {
                return $adapterInstance->convert($jsonData);
            }
        }

        return null;
    }
}