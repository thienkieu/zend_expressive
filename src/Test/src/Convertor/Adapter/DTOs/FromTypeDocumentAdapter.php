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
        $dto->setIsManualScored($document->getIsManualScored());

        $dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $repository = $dm->getRepository(\Test\Documents\Question\TypeDocument::class);
        $subTypeDocuments = $repository->findBy(['parentType' => $document->getId()]);

        $subTypeDTO = [];
        $firstSubDTO = new \Test\DTOs\Question\TypeDTO();
        $firstSubDTO->setId('');
        $firstSubDTO->setName('');
        $firstSubDTO->setIsManualScored(false);
        $subTypeDTO[] =  $firstSubDTO;
        
        foreach($subTypeDocuments as $subTypeDocument) {
            $subDTO = new \Test\DTOs\Question\TypeDTO();
            $subDTO->setId($subTypeDocument->getId());
            $subDTO->setName($subTypeDocument->getName());
            $subDTO->setIsManualScored($subTypeDocument->getIsManualScored());
            $subTypeDTO[] =  $subDTO;
        }
        $dto->setSubTypes($subTypeDTO);
        
        return $dto;
    }
    
}