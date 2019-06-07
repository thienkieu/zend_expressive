<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToCandidateDocumentAdapter implements ConvertAdapterInterface {
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
    
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Exam\CandidateDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Exam\CandidateDocument();
        $document->setName($dto->getName());
        $document->setEmail($dto->getEmail());
        $document->setObjectId($dto->getObjectId());
        $document->setType($dto->getType());

        return $document;
    }
}