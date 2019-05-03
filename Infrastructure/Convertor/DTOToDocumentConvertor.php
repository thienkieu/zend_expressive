<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class DTOToDocumentConvertor implements DTOToDocumentConvertorInterface{
    
    /**
     * @var $adapters ConvertAdapterInterface[]
     */
    private  $adapters;

    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    
    public function convertToDocument($dto) {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter();
            if ($adapterInstance->isHandle($dto)) {
                return $adapterInstance->convert($dto);
            }
        }

        return null;
    }
}