<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

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
        if ($document instanceof \Test\Documents\Test\QuestionDocument) {
            return true;
        }

        return false;
    }

    public function convert($document) {
        $dto = new \Test\DTOs\Test\QuestionDTO();
        $dto->setGenerateFrom($document->getGenerateFrom());
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionInfoDocument = $document->getQuestionInfo();        
        $questionDTO = $documentToDTOConvertor->convertToDTO($questionInfoDocument);

        
        $dto->setQuestionInfo($questionDTO);        
        return $dto;
    }
    
}