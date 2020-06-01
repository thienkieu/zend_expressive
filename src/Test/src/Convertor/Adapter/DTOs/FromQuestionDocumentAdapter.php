<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromQuestionDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Question\QuestionDocument) {
            return true;
        }

        return false;
    }

    protected function getDTOObject() {
        return new \Test\DTOs\Question\QuestionDTO();
    }

    public function convert($document, $options = []) {
        $dto = $this->getDTOObject();

        $dto->setContent($document->getContent());
        $dto->setOrder($document->getOrder());

        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());

        $dto->setTypeId($document->getType()->getId());
        
        $dto->setUser($document->getUser()->getId());
        
        $dto->setPlatform($document->getPlatform()->getName());
        $dto->setPlatformId($document->getPlatform()->getId());

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $answersDocuments = $document->getAnswers();
        $answers = [];
        foreach($answersDocuments as $a) {
            $answers[] = $documentToDTOConvertor->convertToDTO($a, $options);
        }
        $dto->setAnswers($answers);

        return $dto;
    }
    
}