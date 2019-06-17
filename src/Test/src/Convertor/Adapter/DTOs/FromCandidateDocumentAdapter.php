<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromCandidateDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
    private $container;
    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isHandleConvertDocumentToDTO($document, $options = []) : bool
    {
        if ($document instanceof \Test\Documents\Exam\CandidateDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Exam\CandidateDTO();
        $dto->setId($document->getId());
        $dto->setName($document->getName());
        $dto->setEmail($document->getEmail());
        $dto->setType($document->getType());
        $dto->setPin($document->getPin());
        $dto->setObjectId($document->getObjectId());
        $dto->setIsPinValid($document->getIsPinValid());
        
        return $dto;
    }
    
}