<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class DTOToDocumentConvertor implements DTOToDocumentConvertorInterface{
    
    /**
     * @var $adapters ConvertDTOToDocumentAdapterInterface[]
     */
    private  $adapters;
    protected $container;

    public function __construct($container, array $adapters = [])
    {
        $this->container = $container;
        $this->adapters = $adapters;
    }

    
    public function convertToDocument($dto, $options = []) {
        foreach($this->adapters as $adapter) {
            $adapterInstance = new $adapter($this->container, $this);
            if ($adapterInstance->isHandleConvertDTOToDocument($dto, $options)) {
                return $adapterInstance->convert($dto, $options);
            }
        }

        return null;
    }
}