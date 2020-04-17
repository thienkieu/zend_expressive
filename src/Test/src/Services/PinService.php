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
        if ($result['ok'] != 1) {
            $message = $this->translator->translate('There is problem with update pin \'%pin%\', Please refresh new pin.', ['%pin%' => $pin]);
            throw new \Test\Exceptions\GenerateQuestionException($message); 
        }
        
    }

    public function refreshPin($examId, $candiateId, & $outNewPin, & $messages) {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultDocument::class);
        $newPin = \Infrastructure\CommonFunction::generateUniquePin(1);

        $listeningService = $this->container->get(DoExamResultListeningService::class);
        $examResultDocument = $examResultRepository->findOneBy(['examId'=>$examId, 'candidate.id'=>$candiateId]);
        if (!$examResultDocument) {
            $messages[] = $this->translator->translate('Your pin is still valid.');
            return false; 
        }
        $examResultDocument->setLatestConnectionTime(null);
        $examResultDocument->setLatestDisconnect(null);
        
        $needUpdate = $listeningService->correctRemainRepeatListeningQuestion(\Config\AppConstant::DisconnectReason_Network, $examResultDocument);
        if ($needUpdate) {
            $this->dm->flush();
        }       

        $result =  $examRepository->refreshPin($examId, $candiateId, $newPin[0]);
        if ($result['ok'] != 1) {
            $messages[] = $this->translator->translate('There is problem with refresh pin with candidate \'%candidateId%\', Please check with admin.', ['%candidateId%' => $candiateId]);
            return false; 
        }

        $outNewPin = $newPin[0];
        return true;
    }
}
