<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

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
        if ($document instanceof \Test\Documents\Test\QuestionInfoDocument) {
            return true;
        }

        return false;
    }

    public function convert($document, $options = []) {
        $dto = new \Test\DTOs\Test\QuestionDTO();
        $dto->setId($document->getId());
        $dto->setGenerateFrom($document->getGenerateFrom());
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $questionInfoDocument = $document->getQuestionInfo();        
        $questionDTO = $documentToDTOConvertor->convertToDTO($questionInfoDocument, $options);

        
        $dto->setQuestionInfo($questionDTO);
       
        return $dto;
    }
    
}