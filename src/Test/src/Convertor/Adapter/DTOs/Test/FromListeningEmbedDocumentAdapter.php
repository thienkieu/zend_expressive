<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Infrastructure\Convertor\ConvertDocumentToDTOAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromListeningEmbedDocumentAdapter implements ConvertDocumentToDTOAdapterInterface {
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
        if ($document instanceof \Test\Documents\Test\ListeningQuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Question\ListeningQuestionDTO();
        $dto->setContent($document->getContent());
        $dto->setRepeat($document->getRepeat());
        $dto->setPath($this->appendHost($document->getPath()));
        
        $dto->setType($document->getType()->getParentType()->getName());
        $dto->setSubType($document->getType()->getName());
        $dto->setTypeId($document->getType()->getId());

        $dto->setSource($document->getSource()->getName());
        $dto->setSourceId($document->getSource()->getId());
        
        $dto->setId($document->getId());
        $dto->setCandidateMark($document->getCandidateMark());
        $dto->setMark($document->getMark());
        $dto->setNumberCorrectSubQuestion($document->getNumberCorrectSubQuestion());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionDocuments = $document->getSubQuestions();
       
        $questions = [];
        foreach($questionDocuments as $q) {
            $questions[] = $documentToDTOConvertor->convertToDTO($q, $options);
        }
        $dto->setSubQuestions($questions);
        
        return $dto;
    }

    protected function appendHost($content, $options = []) {
        $host = \Infrastructure\CommonFunction::getServerHost();

        $content = $host.$content;
        return $content;
    }
    
}