<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromNonSubQuestionDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Question\NonSubQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\NonSubQuestionDTO();
        
        $content = \Infrastructure\CommonFunction::revertToHost($document->getContent());
        $dto->setContent($content);
        
        $dto->setOrder($document->getOrder());
        $dto->setId($document->getId());

        $dto->setSubType($document->getType()->getName());
        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setTypeId($document->getType()->getId());
        $dto->setRenderType($document->getType()->getRenderName());

        $dto->setPlatform($document->getPlatform()->getName());
        $dto->setPlatformId($document->getPlatform()->getId());
        $dto->setUser($document->getUser()->getId());
        
        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId()); 
        
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