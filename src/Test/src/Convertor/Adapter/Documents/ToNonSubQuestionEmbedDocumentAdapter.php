<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\SourceServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;
use Test\Services\Interfaces\PlatformServiceInterface;

class ToNonSubQuestionEmbedDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\NonSubQuestionDTO && isset($options[\Config\AppConstant::ToDocumentClass])) {
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


        $document = new \Test\Documents\Test\NonSubQuestionDocument();
        $content = \Infrastructure\CommonFunction::replaceHost($dto->getContent());
        $document->setContent($content);
        
        $document->setSource($sourceDocument);
        
        $userService = $this->container->get(\ODMAuth\Services\Interfaces\UserServiceInterface::class);
        $user = $userService->getUserById($dto->getUser());
        $document->setUser($user);
        
        $platformService = $this->container->get(PlatformServiceInterface::class);
        $platformDocument = $platformService->getPlatformById($dto->getPlatformId());
        if (!$platformDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Platform not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setPlatform($platformDocument);
        $document->setPlatformId($platformDocument->getId());

        $typeService = $this->container->get(TypeServiceInterface::class);
        $typeDocument = $typeService->getTypeById($dto->getTypeId());
        if (!$typeDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Type not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setType($typeDocument);
       
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