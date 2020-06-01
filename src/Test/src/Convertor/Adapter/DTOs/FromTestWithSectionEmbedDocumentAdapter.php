<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromTestWithSectionEmbedDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\ExamResult\TestWithSectionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Test\TestWithSectionDTO();
        $dto->setTitle($document->getTitle());
        $dto->setId($document->getId());
        $dto->setReferId($document->getReferId());

        $logger = $this->container->get(\Zend\Log\Logger::class);
       
        $dto->setPlatform($document->getPlatform()->getId());
        $dto->setUser($document->getUser()->getId());
        $sections = $document->getSections();
        $sectionDTO = [];
        foreach($sections as $section) {
            $sectionDTO[] = $this->convertor->convertToDTO($section, $options);
        }
        $dto->setSections($sectionDTO);

        return $dto;
    }
    
}