<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromTestTemplateDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
    private $container;
    private $convertor;
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
        if ($document instanceof \Test\Documents\Test\TestTemplateDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Test\TestTemplateDTO();
        $dto->setTitle($document->getTitle());
        $dto->setId($document->getId());
        $dto->setIsDefault($document->getIsDefault());
        
        $sectionDocuments = $document->getSections();
        $sectionDTO = [];
        foreach($sectionDocuments as $section) {
            $sectionDTO[] = $this->convertor->convertToDTO($section, $options);
        }
        $dto->setSections($sectionDTO);

        return $dto;
    }
    
}