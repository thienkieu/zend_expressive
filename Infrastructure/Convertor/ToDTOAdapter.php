<?php 

declare(strict_types=1);

namespace Infrastructure\Convertor;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ConvertAdapterInterface;

abstract class ToDTOAdapter implements ConvertAdapterInterface {
    
    public function convert($request) 
    {  
        $class = $dtoClass = $this->getDTOClass();
        
        $hydrator = new ReflectionHydrator();        
        $dto = $hydrator->hydrate(
            $request->getParsedBody(),
            (new \ReflectionClass($class))->newInstanceWithoutConstructor()
        );
        
        return $dto;            
    }

    abstract  public function getDTOClass();
}