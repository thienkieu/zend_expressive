<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

use Test\Services\Question\QuestionServiceInterface;

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

    public function doExam($dto, & $results, & $messages) {
        try {
            $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamHasSectionTestDocument::class);
            $document = $testDocuments = $examRepository->getExamInfo($dto->pin);
            if (!$document) {
                $messages[] = $this->translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
                return false;
            }

            $candidates = $document->getCandidates();
            $candidate = $candidates[0];
            if (!$candidate->getIsPinValid()) {
                $messages[] = $this->translator->translate('Your pin \'%pin%\' is not valid, Please notify to admin to get new pin', ['%pin%' => $dto->pin]);
                return false;
            }

            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
            $existingExamResult = $testDocuments = $examResultRepository->getExamResult($document->getId(), $candidate->getId(), '');
            if (!$existingExamResult) {
                $messages[] = $this->translator->translate('Cannot found your exam with pin, Please check with admin.', ['%pin%' => $dto->pin]);
                return false;
            }

            $results = $documentToDTOConvertor->convertToDTO($existingExamResult);
            return true;
            
        }catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();       
            return false;   
        }

    }
}
