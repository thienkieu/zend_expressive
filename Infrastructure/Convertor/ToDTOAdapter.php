<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ConvertAdapterInterface;

abstract class ToDTOAdapter implements ConvertAdapterInterface {
    
    public function convert($request) 
    {  
        $dtoClass = $this->getDTOClass();
       
        $mapper = new \JsonMapper();
        $dto = $mapper->map(\json_decode((string)$request->getBody()), new $dtoClass());
        return $dto;            
    }

    abstract  public function getDTOClass();
}