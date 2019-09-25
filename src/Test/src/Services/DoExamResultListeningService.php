<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;

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
        $repository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $repository->updateDisconnectTime($dto->examId, $dto->candidateId);
    }

    public function getListeningQuestion($examResultDocument) {
        $questions = [];
        $sections = $examResultDocument->getTest()->getSections();
        foreach ($sections as $section) {
            $questionDocuments = $section->getQuestions();
            foreach($questionDocuments as $question) {
                if($question->getType()->getName() === \Config\AppConstant::Listening) {
                    $questions[] = $question;
                }
            }
        }
    } 

    public function isAddMoreTimes($question, $latestDisconnect, & $messages) {
        if ($question->getLatestClick() !== null && $question->getIsFinished() !== true && ($latestDisconnect - $question->getLatestClick() < $question->getDuration())) {
            $remainRepeat = $question->getRepeat();
            $question->setRepeat($remainRepeat +1);
            return true;
        }
    }

    public function correctRemainRepeatListeningQuestion($disconenctReason, & $examResultDocument) {
        if ($disconenctReason !== \Config\AppConstant::DisconnectReason_Refresh) {
            $listeningQuestions = $this->getListeningQuestion($examResultDocument);
            foreach ($listeningQuestions as $questions) {
                $this->isAddMoreTimes($questions, $examResultDocument->getLatestDisconnect());
            }
        }
    }
}
