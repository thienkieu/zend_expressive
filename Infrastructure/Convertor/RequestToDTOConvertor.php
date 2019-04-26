<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class RequestToDTOConvertor implements RequestToDTOConvertorInterface {
    
    /**
     * @var $adapters ConvertAdapterInterface[]
     */
    private  $adapters;

    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    
    public function convertToDTO($request) {
        
        foreach($this->adapters as $adapter) {
            if ($adapter->isHandle($request)) {
                return $adapter->convert($request);
            }
        }

        return null;
    }
}