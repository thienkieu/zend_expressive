<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromTestWithSectionDocumentAdapter implements ConvertAdapterInterface {
    
    private $container;
    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function isHandle($document) : bool
    {
        if ($document instanceof \Test\Documents\Test\TestWithSectionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Test\TestWithSectionDTO();
        $dto->setTitle($document->getTitle());
        $dto->setId($document->getId());

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $sectionDocuments = $document->getSections();
        $sectionDTO = [];
        foreach($sectionDocuments as $section) {
            $sectionDTO[] = $documentToDTOConvertor->convertToDTO($section);
        }
        $dto->setSections($sectionDTO);

        return $dto;
    }
    
}