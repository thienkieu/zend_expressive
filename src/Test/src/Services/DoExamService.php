<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use time;

class DoExamService implements DoExamServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');    
        $this->translator = $this->container->get(\Config\AppConstant::Translator);    
    }

    public function isHandler($dto, $options = []){
        return true;
    }
    
    public function updateAnswer($dto, & $messages) {

        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $testDocuments = $examResultRepository->updateAnwser($dto->examId, $dto->candidateId, $dto->questionId, $dto->subQuestionId, $dto->answerId);
        
        $this->dm->flush();
        if (!$document) {
            $messages[] = $this->translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
            return false;
        }
        
        return true;
        
    }
    
    public function isAllowAccessExam($examDocument) {
        $startDate = $examDocument->getStartDate()->format(\Config\AppConstant::DateTimeFormat);
        $now = (new \DateTime())->format(\Config\AppConstant::DateTimeFormat);

        if ($now === $startDate) return true;
        return false;
    }

    public function inValidPin($examId, $candidateId) {
        $pinInfo = new \stdClass();
        $pinInfo->examId = $examId;
        $pinInfo->candidateId = $candidateId;
        $pinService = $this->container->get(PinServiceInterface::class);
        $pinService->inValidPin($pinInfo->examId, $pinInfo->candidateId);
    }

    public function updateRemainingListeningTime(&$result) {

    }

    public function doExam($dto, & $results, & $messages) {
        try {
            $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
            $examDocument = $examRepository->getExamInfo($dto->pin);
            if (!$examDocument) {
                $messages[] = $this->translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
                return false;
            }
            
            if (!$this->isAllowAccessExam($examDocument)) {
                $messages[] = $this->translator->translate('Your PIN is invalid.');
                return false;
            }

            $doExamAuthorizationService = $this->container->get(\ODMAuth\Services\Interfaces\DoExamAuthorizationServiceInterface::class);
            $examOfCandidateInfo = $doExamAuthorizationService->getCandidateInfo();
            $token = $doExamAuthorizationService->getToken();
            $candidates = $examDocument->getCandidates();
            $candidate = $candidates[0];
            
            if (!$candidate->getIsPinValid() && (!$examOfCandidateInfo || !$examOfCandidateInfo->examId)) {
                $messages[] = $this->translator->translate('Your pin \'%pin%\' is not valid, Please notify to admin to get new pin', ['%pin%' => $dto->pin]);
                return false;
            }

            $trackingConnect = $this->container->get(\Test\Services\TrackingConnectServiceInterface::class);
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
            $examResultDocument = $examResultRepository->getExamResult($examDocument->getId(), $candidate->getId(), '');
            if ($examResultDocument) {
                $isPinValid = $examResultDocument->getCandidate()->getIsPinValid();
                if ($isPinValid) {
                    $latestDisconnectTime = $trackingConnect->getLatestDisconnect($token);
                    $latestConnectTime = $examResultDocument->getLatestConnectionTime();
                    $examRemain = $examResultDocument->getRemainTime();
                   
                    
                    $examTotalSpendingTime = $examResultDocument->getTotalSpendingTime() ? $examResultDocument->getTotalSpendingTime() : 0;
                    $examTime = $examResultDocument->getTime() * 60;

                    $currentSpending = $latestDisconnectTime - $latestConnectTime;
                    $remainTime = $examTime - ($examTotalSpendingTime + $currentSpending);        
                    if ($examRemain > $remainTime) {
                        if ($remainTime > $examTime ) $remainTime = $examTime;
                        if ($currentSpending < 0 ) $currentSpending = 0;
                        $examResultDocument->setRemainTime($remainTime);
                    }                
                            
                    $examResultDocument->setTotalSpendingTime($examTotalSpendingTime + $currentSpending);
                    $examResultDocument->setLatestDisconnect($latestDisconnectTime);
                    
                    
                    $listeningService = $this->container->get(DoExamResultListeningService::class);
                    $needUpdate = $listeningService->correctRemainRepeatListeningQuestion($dto->reason, $examResultDocument);
                    
                    $examResultDocument->setLatestConnectionTime(time());
                    $examResultDocument->setLatestDisconnect(null);
                    $this->dm->flush();                    

                    $results = $documentToDTOConvertor->convertToDTO($examResultDocument);
                    $this->updateRemainingListeningTime($results);
                    $this->inValidPin($examDocument->getId(), $candidate->getId());
                    return true;

                } 
                
                $messages[] = $this->translator->translate('Your pin \'%pin%\' is not valid, Please notify to admin to get new pin', ['%pin%' => $dto->pin]);
                return false;
            }


            $examDTO = $documentToDTOConvertor->convertToDTO($examDocument, [\Config\AppConstant::ShowCorrectAnswer => true]);
            $examService = $this->container->get(ExamServiceInterface::class);            
           
            $examTest = $examService->generateExamTest($examDTO->getTest(), $messages, true);
            
        
            if (!$examTest) {
                return false;
            }
            
            $examDocument->setIsStarted(true);
            
            $examResult = new \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO();
            $examResult->setTest($examTest);
            $examResult->setExamType($examDTO->getType());
            
            $candidateDTO = $documentToDTOConvertor->convertToDTO($candidate);
            $examResult->setCandidate($candidateDTO);
            $examResult->setExamId($examDTO->getId());
            $examResult->setTime($examDTO->getTime());
            $examResult->setTitle($examDTO->getTitle());
            $examResult->setStartDate($examDTO->getStartDate());
            $examResult->setPlatform($examDTO->getPlatform());
            $examResult->setUser($examDTO->getUser());

            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $examResultDocument = $dtoToDocumentConvertor->convertToDocument($examResult, [\Config\AppConstant::ToDocumentClass => \Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class]);
            
            $examResultDocument->setRemainTime($examDTO->getTime() * 60);
            $examResultDocument->setLatestConnectionTime(time());
            $this->dm->persist($examResultDocument);
            $this->dm->flush();

            $this->inValidPin($examDocument->getId(), $candidate->getId());
            
            $results = $documentToDTOConvertor->convertToDTO($examResultDocument);
            return true;
            
        }catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();       
            return false;   
        }

    }
}
