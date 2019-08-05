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

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\ListeningQuestionDTO();
        $dto->setContent($document->getContent());
        $dto->setRepeat($document->getRepeat());
        
        $dto->setPath(\Infrastructure\CommonFunction::revertToHost($document->getPath()));

        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());
        
        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());
        $dto->setId($document->getId());
        $dto->setMark($document->getMark());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionDocuments = $document->getSubQuestions();
        if ($questionDocuments) {
            $questions = [];
            foreach($questionDocuments as $q) {
                $questions[] = $documentToDTOConvertor->convertToDTO($q, $options);
            }
            $dto->setSubQuestions($questions);
        }

        return $dto;
    }
}