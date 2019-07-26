<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\SourceServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;

class ToWritingDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
    protected $container;
    protected $convertor;

    /**
     * Class constructor.
     */
    public function __construct($container, $convertor)
    {
        $this->container = $container;
        $this->convertor = $convertor;
    }
    
    public function isHandleConvertDTOToDocument($dtoObject, $options = []) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Question\WritingQuestionDTO && !isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }

        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Question\WritingQuestionDocument();
        $document->setContent($dto->getContent());

        $sourceService = $this->container->get(SourceServiceInterface::class);
        $sourceDocument = $sourceService->getSourceByName($dto->getSource());
        if (!$sourceDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Source not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setSource($sourceDocument);

        $typeService = $this->container->get(TypeServiceInterface::class);
        $typeDocument = $typeService->getTypeById($dto->getTypeId());
        if (!$typeDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Type not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setType($typeDocument);


        return $document;
            
    }
}