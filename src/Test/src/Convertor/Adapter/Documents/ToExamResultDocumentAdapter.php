<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToExamResultDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\ExamResult\ExamResultHasSectionTestDocument();
        $document->setExamId($dto->getExamId());
        $document->setTitle($dto->getTitle());
        $document->setStartDate($dto->getStartDate());
        $document->setTime($dto->getTime());
        $document->setExamType($dto->getExamType());
        
        $userService = $this->container->get(\ODMAuth\Services\Interfaces\UserServiceInterface::class);
        $user = $userService->getUserById($dto->getUser());
        $document->setUser($user);

        $platformService = $this->container->get(\Test\Services\Interfaces\PlatformServiceInterface::class);
        $platformDocument = $platformService->getPlatformById($dto->getPlatform());
        if (!$platformDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Platform not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setPlatform($platformDocument);

        $candidateDocument = $this->convertor->convertToDocument($dto->getCandidate(), $options);
        $document->setCandidate($candidateDocument); 

        $test = $this->convertor->convertToDocument($dto->getTest(), $options);
        $document->setTest($test);
        
        return $document;
            
    }
}