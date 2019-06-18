<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromListeningDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Question\ListeningQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Question\ListeningQuestionDTO();
        $dto->setContent(json_decode($document->getContent()));
        $dto->setRepeat($document->getRepeat());
        $dto->setPath($document->getPath());
        $dto->setType($document->getType());
        $dto->setSubType($document->getSubType());
        $dto->setSource($document->getSource());
        $dto->setId($document->getId());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionDocuments = $document->getSubQuestions();
       
        $questions = [];
        foreach($questionDocuments as $q) {
            $questions[] = $documentToDTOConvertor->convertToDTO($q);
        }
        $dto->setSubQuestions($questions);

        return $dto;
    }
    
}