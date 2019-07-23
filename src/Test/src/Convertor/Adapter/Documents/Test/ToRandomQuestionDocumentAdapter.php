<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\Test;


use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\TypeServiceInterface;

class ToRandomQuestionDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Test\RandomQuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Test\RandomQuestionDocument();

        $typeService = $this->container->get(TypeServiceInterface::class);
        $typeDocument = $typeService->getTypeByName($dto->getType(), $dto->getSubType());
        if (!$typeDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Type not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setType($typeDocument);

        $document->setIsDifferentSource($dto->getIsDifferentSource());
        $document->setNumberSubQuestion($dto->getNumberSubQuestion());
        $document->setReferId(uniqid('', true));
        $document->setMark($dto->getMark());
       
        return $document;
            
    }
}