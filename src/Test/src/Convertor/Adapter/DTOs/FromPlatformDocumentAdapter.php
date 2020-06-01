<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromPlatformDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
    private $container;
    /**
     * Class constructor.
     */
    public function __construct($container, $convertor)
    {
        $this->container = $container;
        $this->convertor = $convertor;
    }

    public function isHandleConvertDocumentToDTO($document, $options = []) : bool
    {
        if ($document instanceof \Test\Documents\PlatformDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\PlatformDTO();
        $dto->setId($document->getId());
        $dto->setName($document->getName());
        
        $dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $repository = $dm->getRepository(\Test\Documents\PlatformDocument::class);
        
        $subPlatformDocuments = $repository->findBy(['parentPlatform' => $document->getId()]);

        $subPlatformDTO = [];
        
        foreach($subPlatformDocuments as $subPlatformDocument) {
            $subDTO = new \Test\DTOs\PlatformDTO();
            $subDTO->setId($subPlatformDocument->getId());
            $subDTO->setName($subPlatformDocument->getName());
            $subPlatformDTO[] =  $subDTO;
        }
        $dto->setSubPlatforms($subPlatformDTO);
        
        return $dto;
    }
    
}