<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;
use time;
class DoExamResultListeningService implements DoExamResultListeningServiceInterface, HandlerInterface
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
        return true;
    }

    public function updateDisconnect($dto, & $messages) {
        $currentTime = time();

        $repository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $examResultDocument = $repository->findOneBy(['examId' => $dto->examId, 'candidate.id' => $dto->candidateId]);
        $examRemain = $examResultDocument->getRemainTime();
        $examTotalSpendingTime = $examResultDocument->getTotalSpendingTime() ? $examResultDocument->getTotalSpendingTime() : 0;
        $examTime = $examResultDocument->getTime();

        $startTime = $examResultDocument->getLatestConnectionTime();
        $remainTime = $examTime - ($examTotalSpendingTime + ($currentTime - $startTime));
        if ($examRemain > $remainTime) {
            $examResultDocument->setRemainTime($remainTime);
        }                
        
        $examResultDocument->setTotalSpendingTime($examTotalSpendingTime + ($currentTime - $startTime));
        $examResultDocument->setLatestDisconnect($currentTime);

        $this->dm->flush();

        return true;
    }

    public function getListeningQuestion($examResultDocument) {
        $questions = [];
        $sections = $examResultDocument->getTest()->getSections();
        foreach ($sections as $section) {
            $questionDocuments = $section->getQuestions();
            foreach($questionDocuments as $question) {
                if($question->getQuestionInfo()->getType()->getParentType()->getName() === \Config\AppConstant::Listening) {
                    $questions[] = $question;
                }
            }
        }
    } 

    public function isAddMoreTimes($question, $latestDisconnect, & $messages) {
        if ($question->getLatestClick() !== null && $question->getIsFinished() !== true && ($latestDisconnect - $question->getLatestClick() < $question->getDuration())) {
            $remainRepeat = $question->getRepeat();
            $question->setRepeat($remainRepeat +1);
            $question->setLatestClick(null);
            return true;
        }
    }

    public function correctRemainRepeatListeningQuestion($disconenctReason, & $examResultDocument) {
        $ret = false;
        if ($disconenctReason !== \Config\AppConstant::DisconnectReason_Refresh) {
            $listeningQuestions = $this->getListeningQuestion($examResultDocument);
            foreach ($listeningQuestions as $questions) {
                $needUpdate = $this->isAddMoreTimes($questions, $examResultDocument->getLatestDisconnect());
                if ($needUpdate) {
                    $ret = true;
                }
            }

            $examResultDocument->setLatestDisconnect(null);
           
        }
        
        return $ret;
    }
}
