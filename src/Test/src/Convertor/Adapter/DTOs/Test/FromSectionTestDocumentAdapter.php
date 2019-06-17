<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromSectionTestDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Test\SectionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Test\SectionDTO();
        $dto->setName($document->getName());
        $dto->setDescription($document->getDescription());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionDocuments = $document->getQuestions();
        $questionDTOs = [];
        foreach($questionDocuments as $question) {
            $questionDTOs[] = $documentToDTOConvertor->convertToDTO($question);
        }
        $dto->setQuestions($questionDTOs);

        return $dto;
    }
    
}