<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ConvertAdapterInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;

class FromAnswerDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($document) : bool
    {
        if ($document instanceof \Test\Documents\Question\AnswerDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Question\AnswerDTO();
        $dto->setContent($document->getContent());
        $dto->setIsCorrect($document->getIsCorrect());
        $dto->setOrder($document->getOrder());

        return $dto;
    }
    
}