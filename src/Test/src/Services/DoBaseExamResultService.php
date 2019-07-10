<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

use Test\Services\Question\QuestionServiceInterface;

class DoBaseExamResultService implements DoExamResultServiceInterface, HandlerInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');    
        $this->translator = $this->container->get(\Config\AppConstant::Translator);    
    }

    public function isHandler($dto, $options = []){
        if (empty($dto->writingContent) && empty($dto->answers)) {
            return true;
        }

        return false;
    }

    public function isExistResultOfExam($examId) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->findBy(['examId' =>$examId]);
        return !!$examResult;
    }

    public function getExamResult($dto, & $messages, & $examResultDTO) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->getExamResult($dto->examId, $dto->candidateId, '');
        if (!$examResult) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class, [\Config\AppConstant::ShowCorrectAnswer => true]);
        $examResultDTO = $documentToDTOConvertor->convertToDTO($examResult);
        return true;
    }

    public function updateDoneExamResult($examResultId) {
        
    }

    public function updateQuestionMark($dto, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), '');
        if (!$examResult) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $questionDocument = $this->getQuestion($examResult, $dto);
        if (!$questionDocument) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $questionDocument->setComment($dto->getComment());

        $questionService  = $this->container->get(QuestionServiceInterface::class);
        $questionService->setCandidateMark($questionDocument, $dto->getMark());
        
        $this->dm->flush();
        $hasQuestionNotScored = $examResultRepository->hasQuestionNotScored($examResult->getId());
        if (!$hasQuestionNotScored) {
            $examResult->setIsDone(true);
        }
        $this->updateResultSummary($examResult);

        $this->dm->flush();
        return true;
    }

    protected function updateResultSummary($examResult) {
        $adapter = new \Test\Convertor\Adapter\Documents\ToExamResultSummaryDocumentAdapter(null, null);
        $summaries = $adapter->convert($examResult);
        $examService = $this->container->get(ExamServiceInterface::class);
        $examService->updateExamResultSummary($examResult->getExamId(), $examResult->getCandidate()->getId(), $summaries);
    }

    public function finish($dto, & $messages) {
        $outRemainTime = 0;
        $dto->remainTime = 0;

        $ret = $this->synchronyTime($dto, $outRemainTime, $messages);
        $ret = $this->calculatorExamMark($dto, $messages);
        $ret = $this->inValidPin($dto, $messages);

        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->getExamResult($dto->examId, $dto->candidateId, '');
        $ret = $this->updateResultSummary($examResult);
        
        $this->dm->flush();
        return $ret;
    }
    
    protected function inValidPin($dto, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $examResultRepository->getExamResult($dto->examId, $dto->candidateId, '');
        if (!$document) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $pinService = $this->container->get(PinServiceInterface::class);
        $pinService->inValidPin($dto->examId, $dto->candidateId);
        $examResultRepository->inValidPinByCandidateId($dto->examId, $dto->candidateId);

        return true;
    }
    protected function calculatorExamMark($dto, & $messages){
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $examResultRepository->getExamResult($dto->examId, $dto->candidateId, '');
        if (!$document) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $sections = $document->getTest()->getSections();
        foreach ($sections as $section) {
            $questions = $section->getQuestions();
            foreach ($questions as $question) {
                $questionInfo = $question->getQuestionInfo();
                $questionService = $this->container->build(QuestionServiceInterface::class, ['document'=>$questionInfo]);
                $questionService->caculateMark($questionInfo);
            }
        }

        $this->dm->flush();
        return true;
    }

    public function synchronyTime($dto, & $outRemainTime, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $examResultRepository->getExamResult($dto->examId, $dto->candidateId, '');
        if ($document) {
            $remainTime = $document->getRemainTime();
            if ($dto->remainTime < $remainTime) {
                $document->setRemainTime($dto->remainTime);
                $outRemainTime = $dto->remainTime;
            } else {
                $r = $remainTime - \Config\AppConstant::ReduceTimeSpan;
                $document->setRemainTime($r);
                $outRemainTime = $r;
            }
            
            $this->dm->flush();
            return true;
        }

        $messages[] = $this->translator->translate('Exam not found');
        return false;
        
    }

    public function updateAnswer($dto, & $messages) {
        try {
            $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
            $document = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), $dto->getQuestionId());
            if (!$document) {
                $messages[] = $this->translator->translate('Exam not found');
                return false;
            }
            
            $remaintTime = $document->getRemainTime();
            if ($remaintTime <= 0) {
                $messages[] = $this->translator->translate('Your test have been finished!');
                return false; 
            }

            if (!$document) {
                $messages[] = $this->translator->translate('There isnot exist question with', ['%questionId%' => $dto->getQuestionId()]);
                return false;
            }
            
            $this->updateSubQuestionAnswer($document, $dto);
            $this->dm->flush();

            return true;
        }catch(\Test\Exceptions\UpdateAnswerException $ex) {
            $messages[] = $ex->getMessage();
            return false;
        }
    }

    protected function updateSubQuestionAnswer(& $examResult, $dto) {        
    
    }

    protected function getQuestion(& $examResult, $dto) {
        $sections = $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            if ($section->getId() == $dto->getSectionId()) {
                $questions = $section->getQuestions();
                foreach ($questions as $question) {
                    if ($question->getId() == $dto->getQuestionId()) {
                        return $question->getQuestionInfo();                        
                    }
                }

                break;
            }
        }

        return null;
    }
}
