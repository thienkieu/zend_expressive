<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromTypeDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Question\TypeDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\TypeDTO();
        $dto->setId($document->getId());
        $dto->setName($document->getName());
        $dto->setRenderName($document->getRenderName());
        $dto->setIsManualScored($document->getIsManualScored());
        $dto->setPlatform($document->getPlatform()->getId());

        $dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $repository = $dm->getRepository(\Test\Documents\Question\TypeDocument::class);
        
        $subTypeDocuments = $repository->findBy(['parentType' => $document->getId()]);

        
        $subTypeDTO = [];
        
        foreach($subTypeDocuments as $subTypeDocument) {
            $subDTO = new \Test\DTOs\Question\TypeDTO();
            $subDTO->setId($subTypeDocument->getId());
            $subDTO->setName($subTypeDocument->getName());
            $subDTO->setIsManualScored($subTypeDocument->getIsManualScored());
            $subDTO->setPlatform($subTypeDocument->getPlatform()->getId());
            $subDTO->setRenderName($subTypeDocument->getRenderName());
            $subTypeDTO[] =  $subDTO;
        }
        $dto->setSubTypes($subTypeDTO);
        
        return $dto;
    }
    
}