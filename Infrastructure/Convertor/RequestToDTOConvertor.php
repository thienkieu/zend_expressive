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

    
    public function convertToDTO($jsonData, $options = []) {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter($this->container, $this);
            if ($adapterInstance->isHandleConvertToDTO($jsonData, $options)) {
                return $adapterInstance->convert($jsonData, $options);
            }
        }

        if (isset($options[\Config\AppConstant::DTOKey])) {
            $dtoClass = $options[\Config\AppConstant::DTOKey];
       
            $mapper = new \JsonMapper();
            $mapper->bStrictNullTypes = false;
            $dto = $mapper->map($jsonData, new $dtoClass());
            return $dto; 
        }

        return null;
    }
}