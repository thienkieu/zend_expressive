<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromListeningDocumentAdapter implements ConvertAdapterInterface {
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
        if ($document instanceof \Test\Documents\ListeningSectionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\ListeningSectionDTO();
        $dto->setContent($document->getContent());
        $dto->setRepeat($document->getRepeat());
        $dto->setPath($document->getPath());
        
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