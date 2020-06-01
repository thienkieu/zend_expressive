<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\SourceServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;
use Test\Services\Interfaces\PlatformServiceInterface;

class ToReadingEmbedDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\ReadingQuestionDTO && isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }

        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Test\ReadingQuestionDocument();
        $content =\Infrastructure\CommonFunction::replaceHost($dto->getContent());
        $document->setContent($content);

        $sourceService = $this->container->get(SourceServiceInterface::class);
        $sourceDocument = $sourceService->getSourceByName($dto->getSource());
        if (!$sourceDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Source not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
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
        
        $document->setReferId($dto->getId());
        
        
        $questions = $dto->getSubQuestions();

        foreach($questions as $question) {
            $questionDocument = $this->convertor->convertToDocument($question, $options);
            $document->addSubQuestion($questionDocument);            
        }
        
        $mark = $dto->getMark();
        if (!$mark) {
            $mark = count($questions) * \Config\AppConstant::DefaultSubQuestionMark;
        }
        $document->setMark($mark);
        
        return $document;
            
    }
}