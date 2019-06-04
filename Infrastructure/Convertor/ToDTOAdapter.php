<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Infrastructure\Convertor\ConvertToDTOAdapterInterface;

abstract class ToDTOAdapter implements ConvertToDTOAdapterInterface {
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

    public function convert($jsonObject) 
    {  
        $dtoClass = $this->getDTOClass();
       
        $mapper = new \JsonMapper();
        $dto = $mapper->map($jsonObject, new $dtoClass());
        return $dto;            
    }

    abstract  public function getDTOClass();
}