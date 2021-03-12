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

    public function removeExamResultByExamId($examId) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResultRepository->removeResultByExamId($examId);
        return true;
    }

    public function isExistExamResultMarkDone($examId) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->findBy(['examId' =>$examId, 'isDone' => true]);
        return !!$examResult;
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

        $examResultDTO = $documentToDTOConvertor->convertToDTO($examResult, [\Config\AppConstant::ShowCorrectAnswer => true]);
        return true;
    }

    public function updateDoneExamResult($examResultId) {
        
    }

    public function isExamResultScored($examResult) {
        $sections =  $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            if (!$this->isScoredSection($section)) {
                return false;
            }
        }

        return true;
    }

    public function isScoredSection($section) {
        $isOptionSection = $section->getIsOption();
        if ($isOptionSection) {
            $numberQuestionRequired = $section->getRequiredQuestion();
            $questions = $section->getQuestions();
            $aNumberQuestionScored = 0;
            foreach ($questions as $question) {
                $questionInfo = $question->getQuestionInfo(); 
                if ($questionInfo->getIsScored()) {
                    $aNumberQuestionScored += 1;
                }                                               
            }

            return $aNumberQuestionScored >= $numberQuestionRequired;
        } else {
            $questions = $section->getQuestions();
            foreach ($questions as $question) {
                $questionInfo = $question->getQuestionInfo(); 
                if (!$questionInfo->getIsScored()) {
                    return false;
                }                                               
            }

            return true;
        }
    }

    public function updateSectionMark($dto, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), '');
        if (!$examResult) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $sectionDocument = $this->getSection($examResult, $dto);
        if (!$sectionDocument) {
            $messages[] = $this->translator->translate('Section not found');
            return false;
        }

        $sectionDocument->setToeicExpirationDate($dto->getToeicExpirationDate());
        $sectionDocument->setIsToeic($dto->getIsToeic());
        $sectionDocument->setComment($dto->getComment());
        $sectionDocument->setCandidateMark($dto->getMark());
        $questionDocuments = $sectionDocument->getQuestions();
        foreach($questionDocuments as $questionDocument) {
            $questionDocument->getQuestionInfo()->setIsScored(true);
        }

        $this->dm->flush();
        $examResult = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), '');
        /*$hasQuestionNotScored = $examResultRepository->hasQuestionNotScored($examResult->getId());
        if (!$hasQuestionNotScored) {
            $examResult->setIsDone(true);
            $this->dm->flush();
        }*/

        $isScored = $this->isExamResultScored($examResult);
        if ($isScored) {
            $examResult->setIsDone(true);
            $this->dm->flush();
        }

        $this->updateResultSummary($dto->getExamId(), $dto->getCandidateId());
        
        
        return true;
    }

    public function updateQuestionMark($dto, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), '');
        if (!$examResult) {
            $messages[] = $this->translator->translate('Exam not found');
            return false;
        }

        $section = $this->getSection($examResult, $dto);
        $questionDocument = $this->getQuestion($examResult, $dto);
        if (!$questionDocument) {
            $messages[] = $this->translator->translate('Question not found');
            return false;
        }

        $questionDocument->setComment($dto->getComment());
        $section->setCandidateMark(null);
        
        $questionService  = $this->container->get(QuestionServiceInterface::class);
        $questionService->setCandidateMark($questionDocument, $dto->getMark());
        
        $this->dm->flush();
        $examResult = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), '');
        /*$hasQuestionNotScored = $examResultRepository->hasQuestionNotScored($examResult->getId());
        if (!$hasQuestionNotScored) {
            $examResult->setIsDone(true);
            $this->dm->flush();
        }*/

        $isScored = $this->isExamResultScored($examResult);
        if ($isScored) {
            $examResult->setIsDone(true);
            $this->dm->flush();
        }
        $this->updateResultSummary($dto->getExamId(), $dto->getCandidateId());
        
        
        return true;
    }

    protected function updateResultSummary($examId, $candidateId) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResult = $examResultRepository->getExamResult($examId, $candidateId, '');

        $adapter = new \Test\Convertor\Adapter\Documents\ToExamResultSummaryDocumentAdapter($this->container, null);
        $summaries = $adapter->convert($examResult);
        
        $examService = $this->container->get(ExamServiceInterface::class);
        $examService->updateExamResultSummary($examResult->getExamId(), $examResult->getCandidate()->getId(), $summaries);
        $examService->updateExamStatus($examResult->getExamId());
        $examResult->setResultSummary($summaries);
        
        $this->dm->flush();
        return;
    }

    protected function isPinValid($examId, $candidateId) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $examResultRepository->getExamResult($examId, $candidateId, '');
        return $document->getCandidate()->getIsPinValid();        
    }

    public function finish($dto, & $messages) {
        $outRemainTime = 0;
        $dto->remainTime = 0;
        /*$isPinValid = $this->isPinValid($dto->examId, $dto->candidateId);
        if (!$isPinValid) {
            $messages[] = $this->translator->translate('Your cannot finish this exam because this exam have been finished!');
            return true;
        }*/
        $ret = $this->synchronyTime($dto, $outRemainTime, $messages);
        $ret = $this->calculatorExamMark($dto, $messages);
        $ret = $this->inValidPin($dto, $messages);
        $ret = $this->updateResultSummary($dto->examId, $dto->candidateId);

        return true;
    }
    
    public function finishRestore($dto, & $messages) {
        $outRemainTime = 0;
        $dto->remainTime = 0;
        /*$isPinValid = $this->isPinValid($dto->examId, $dto->candidateId);
        if (!$isPinValid) {
            $messages[] = $this->translator->translate('Your cannot finish this exam because this exam have been finished!');
            return true;
        }*/
        //$ret = $this->synchronyTime($dto, $outRemainTime, $messages);
        $ret = $this->calculatorExamMark($dto, $messages);
        //$ret = $this->inValidPin($dto, $messages);
        $ret = $this->updateResultSummary($dto->examId, $dto->candidateId);

        return true;
    }
    
    public function inValidPin($dto, & $messages) {
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

            $isPinValid = $this->isPinValid($dto->getExamId(), $dto->getCandidateId());
            if (!$isPinValid) {
                $messages[] = $this->translator->translate('Your pin ins not valid.');
                return false;
            }

            $remaintTime = $document->getRemainTime();
            if ($remaintTime <= 0) {
                $messages[] = $this->translator->translate('Exam is timeout!');
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

    public function updateAnswerResult($dto, & $messages) {
        try {
            
            $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
            $document = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), $dto->getQuestionId());
            if (!$document) {
                $messages[] = $this->translator->translate('Exam not found');
                return false;
            }

            /*$isPinValid = $this->isPinValid($dto->getExamId(), $dto->getCandidateId());
            if (!$isPinValid) {
                $messages[] = $this->translator->translate('Your pin ins not valid.');
                return false;
            }

            $remaintTime = $document->getRemainTime();
            if ($remaintTime <= 0) {
                $messages[] = $this->translator->translate('Exam is timeout!');
                return false; 
            }*/

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
        $ex = new \Test\Exceptions\UpdateAnswerException('There isnot service to handle update answer');
        throw $ex;
    }

    protected function getSection(& $examResult, $dto) {
        $sections = $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            if ($section->getId() == $dto->getSectionId()) {
                return $section;
            }
        }

        return null;
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


    public function addManualResult($examResultDTO, &$messages) {
        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $document = $dtoToDocumentConvertor->convertToDocument($examResultDTO, [\Config\AppConstant::ToDocumentClass => \Test\Documents\ExamResult\TestWithSectionDocument::class]);
        $this->dm->persist($document);
        $this->dm->flush();

        $messages= [];
        $messages[] = $this->translator->translate('Your exam result have been added successfully!');
        return true;
    }

    public function getLatestExamJoined(& $exams, $dto) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $documents = $examResultRepository->getLatestExamJoined($dto);

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $exams = [];
        foreach ($documents as $exam) {
            //echo '<pre>'.print_r($exam, true).'</pre>'; die;
            $dto = $documentToDTOConvertor->convertToDTO($exam);
            $exams[] = $dto;
        }
        
        return true;
    }

    public function getExamJoined(& $exams, $dto) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $documents = $examResultRepository->getExamJoined($dto);

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $exams = [];
        foreach ($documents as $exam) {
            $dto = $documentToDTOConvertor->convertToDTO($exam);
            $exams[] = $dto;
        }
        
        return true;
    }

    public function getListAudioOfExam($dto, & $messages, & $outDTO) {
        $result = $this->getExamResult($dto, $messages, $examResultDTO);
        $listeningQuestions = [];
        if ($result) {
            $test = $examResultDTO->getTest();
            $sections = $test->getSections();
            foreach($sections as $section) {
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question instanceof \Test\DTOs\Question\ListeningQuestionDTO) {
                        $listeningQuestions.push($question);
                    }
                }
            }
            
            $outDTO = $listeningQuestions;
            return true;
        }
        $outDTO = null;
        return false;
        
    }
}
