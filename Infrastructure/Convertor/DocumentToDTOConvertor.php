<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class DocumentToDTOConvertor implements DocumentToDTOConvertorInterface{
    
    /**
     * @var $adapters ConvertAdapterInterface[]
     */
    private  $adapters;

    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    
    public function convertToDTO($document) {
        foreach($this->adapters as $adapter) {
            if ($adapter->isHandle($document)) {
                return $adapter->convert($document);
            }
        }

        return null;
    }
}