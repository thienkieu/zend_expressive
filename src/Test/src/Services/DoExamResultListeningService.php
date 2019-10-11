<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;
use time;
use property_exists;
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
        $examTime = $examResultDocument->getTime() * 60;

        $startTime = $examResultDocument->getLatestConnectionTime();
        $currentSpending = $currentTime - $startTime;
        $remainTime = $examTime - ($examTotalSpendingTime + $currentSpending);        
        if ($examRemain > $remainTime) {
            if (property_exists($dto, 'reason') && $dto->reason === 'ping timeout') {
                $remainTime += 15;
                $currentSpending -= 15;
            }

            if (property_exists($dto, 'reason') && $dto->reason === 'client namespace disconnect') {
                $remainTime += 7;
                $currentSpending -= 7;
            }

            if ($remainTime > $examTime ) $remainTime = $examTime;
            if ($currentSpending < 0 ) $currentSpending = 0;
            $examResultDocument->setRemainTime($remainTime);
        }                
        		
        $examResultDocument->setTotalSpendingTime($examTotalSpendingTime + $currentSpending);
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
                    $questions[] = $question->getQuestionInfo();
                }
            }
        }

        return $questions;
    } 

    public function isAddMoreTimes(&$question, $latestDisconnect, & $messages) {
        if ($question->getLatestClick() !== null && $question->getIsFinished() !== true && ($latestDisconnect - $question->getLatestClick() < $question->getDuration())) {
            $remainRepeat = $question->getRepeat();
            $question->setRepeat($remainRepeat +1);
            $question->setLatestClick(null);
            return true;
        }
    }

    public function correctRemainRepeatListeningQuestion($disconenctReason, & $examResultDocument) {
        $ret = false;
        $messages = [];
        if ($disconenctReason !== \Config\AppConstant::DisconnectReason_Refresh) {
            $listeningQuestions = $this->getListeningQuestion($examResultDocument);
            foreach ($listeningQuestions as $question) {
                $this->isAddMoreTimes($question, $examResultDocument->getLatestDisconnect(), $messages);
            }
           
            //$examResultDocument->setLatestConnectionTime(null);
            //$examResultDocument->setLatestDisconnect(null);
           
        } else {
            $listeningQuestions = $this->getListeningQuestion($examResultDocument);
            foreach ($listeningQuestions as $question) {
                if ($question->getLatestClick() !== null ) {
                    $question->setLatestClick(null);
                }
            }

           // $examResultDocument->setLatestConnectionTime(null);
           // $examResultDocument->setLatestDisconnect(null);
        }
        
        return true;
    }
}
