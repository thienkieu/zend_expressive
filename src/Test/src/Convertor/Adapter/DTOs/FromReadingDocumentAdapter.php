<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromReadingDocumentAdapter implements ConvertAdapterInterface {
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
        if ($document instanceof \Test\Documents\ReadingSectionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\ReadingSectionDTO();
        $dto->setContent($document->getContent());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionDocuments = $document->getQuestions();
        $questions = [];
        foreach($questionDocuments as $q) {
            $questions[] = $documentToDTOConvertor->convertToDTO($q);
        }
        $dto->setQuestions($questions);

        return $dto;
    }
    
}