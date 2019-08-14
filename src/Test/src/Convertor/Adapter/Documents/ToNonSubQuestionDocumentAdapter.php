<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\SourceServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;

class ToNonSubQuestionDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\NonSubQuestionDTO && !isset($options[\Config\AppConstant::ToDocumentClass])) {
        
            return true;
        }

        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
       
        $sourceService = $this->container->get(SourceServiceInterface::class);
        $sourceDocument = $sourceService->getSourceByName($dto->getSource());
        if (!$sourceDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Source not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }


        $document = new \Test\Documents\Question\NonSubQuestionDocument();
        if (isset($options[\Config\AppConstant::ExistingDocument]))  {
            $document = $options[\Config\AppConstant::ExistingDocument];            
        }
        
        $content = \Infrastructure\CommonFunction::replaceHost($dto->getContent());
        $document->setContent($content);
        $id = $dto->getId();
        if (!empty($id)) {
            $document->setId($id);
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
        $document->setTypeId($typeDocument->getId());
        $document->setSubTypeId($typeDocument->getParent()->getId());
        
        $answers = $dto->getAnswers();
        foreach($answers as $answer){
            $a = new \Test\Documents\Question\AnswerDocument();
            $a->setContent($answer->getContent());
            $a->setIsCorrect(!!$answer->getIsCorrect());
            $a->setIsUserChoice(!!$answer->getIsUserChoice());
            $a->setOrder($answer->getOrder());  
            $document->addAnswer($a);
        }
        
        return $document;            
    }
}