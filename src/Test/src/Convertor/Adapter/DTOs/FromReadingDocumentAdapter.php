<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromReadingDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Question\ReadingQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\ReadingQuestionDTO();

        $content = \Infrastructure\CommonFunction::revertToHost($document->getContent());
        $dto->setContent($content);
        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());

        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());
        $dto->setRenderType($document->getType()->getRenderName());
        
        $dto->setUser($document->getUser()->getId());
        
        $dto->setPlatform($document->getPlatform()->getName());
        $dto->setPlatformId($document->getPlatform()->getId());

        $dto->setId($document->getId());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $numberSubQuestion = 0;
        $questionDocuments = $document->getSubQuestions();
        if ($questionDocuments) {
            $questions = [];
            foreach($questionDocuments as $q) {
                $questions[] = $documentToDTOConvertor->convertToDTO($q, $options);
            }
            $dto->setSubQuestions($questions);
            $numberSubQuestion += 1;       
        }

        $mark = $document->getMark();
        if(!$mark) {
            $mark = $numberSubQuestion * \Config\AppConstant::DefaultSubQuestionMark;
        }
        $dto->setMark($document->getMark($mark));
        
        return $dto;
    }
    
}