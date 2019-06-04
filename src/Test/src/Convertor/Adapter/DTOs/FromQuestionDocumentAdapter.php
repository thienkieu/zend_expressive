<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromQuestionDocumentAdapter implements ConvertAdapterInterface {
    
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
        if ($document instanceof \Test\Documents\Question\QuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Question\QuestionDTO();
        $dto->setContent(json_decode($document->getContent()));
        $dto->setOrder($document->getOrder());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $answersDocuments = $document->getAnswers();
        $answers = [];
        foreach($answersDocuments as $a) {
            $answers[] = $documentToDTOConvertor->convertToDTO($a);
        }
        $dto->setAnswers($answers);

        return $dto;
    }
    
}