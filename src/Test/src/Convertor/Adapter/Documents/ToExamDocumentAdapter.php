<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ToExamDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Exam\ExamHasSectionTestDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Exam\ExamDocument();
        if (isset($options[\Config\AppConstant::ExistingDocument])) {
            $document = $options[\Config\AppConstant::ExistingDocument];            
        }
        $document->setTitle($dto->getTitle());
        $document->setTime($dto->getTime());
        $document->setStartDate($dto->getStartDate());
        $document->setType($dto->getType());
        $candidates = $dto->getCandidates();

        $platformService = $this->container->get(\Test\Services\Interfaces\PlatformServiceInterface::class);
        $platformDocument = $platformService->getPlatformById($dto->getPlatform());
        if (!$platformDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Platform not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setPlatform($platformDocument);

        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $user = $authorizationService->getUser();
        $document->setUser($user);


        $candidateDocuments = new ArrayCollection();
        foreach($candidates as $candidate) {
            $candidateDocument = $this->convertor->convertToDocument($candidate, $options);
            $candidateDocuments->add($candidateDocument);            
        }

        $document->setCandidates($candidateDocuments);
        $test = $this->convertor->convertToDocument($dto->getTest(), $options);
        $document->setTest($test);
        
        return $document;
            
    }
}