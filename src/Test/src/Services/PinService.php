<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class PinService implements PinServiceInterface, HandlerInterface
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

    public function showPinInfo($dto, & $results, & $messages) {
        $testRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $document = $testDocuments = $testRepository->getCandidateInfo($dto->pin);
        if ($document) {
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $results = $documentToDTOConvertor->convertToDTO($document);
            return true;
        }
        
        $translator = $this->container->get(\Config\AppConstant::Translator);
        $messages[] = $translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
        return false;
    }

    public function inValidPin($examId, $candidateId) {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $result =  $examRepository->inValidPinByCandidateId($examId, $candidateId);
        if ($result->isAcknowledged() !== true) {
            $message = $this->translator->translate('There is problem with update pin \'%pin%\', Please refresh new pin.', ['%pin%' => $pin]);
            throw new \Test\Exceptions\GenerateQuestionException($message); 
        }
        
    }

    public function refreshPin($examId, $candiateId, & $outNewPin, & $messages) {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultDocument::class);
        $newPin = \Infrastructure\CommonFunction::generateUniquePin(1);

        $examDocument = $examRepository->find($examId);
        $doExamService = $this->container->get(\Test\Services\DoExamServiceInterface::class);
        if (!$doExamService->isAllowAccessExam($examDocument)){
            $messages[] = $this->translator->translate('Cannot refresh pin. Your exam was over.');
            return false; 
        }

        $examResult = $examResultRepository->findOneBy([
            'examId' => $examId,
            'candidate.id' => $candiateId
        ]);
        if ($examResult) {
            $resultSummary = $examResult->getResultSummary();
            if (count($resultSummary) > 0) {
                $messages[] = $this->translator->translate('Cannot refresh PIN. This candidate finished the exam.');
                return false; 
                
            }
        }
        $examStatus =  $examRepository->refreshPin($examId, $candiateId, $newPin[0]);
        $examResultStatus =  $examRepository->refreshPin($examId, $candiateId, $newPin[0]);
        
        if ($examStatus->isAcknowledged() !== true ) {
            $messages[] = $this->translator->translate('There is problem with refresh pin with candidate \'%candidateId%\', Please check with admin.', ['%candidateId%' => $candiateId]);
            return false; 
        }

        $outNewPin = $newPin[0];
        return true;
    }
}
