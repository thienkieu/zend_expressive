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

    public function isAddMoreTimes($question, & $messages) {
        if ($question->getLatestClick() !== null && $question->getIsFinished() !== true && ($question->getLatestDisconnect() - $question->getLatestClick() <$questin->getDuration())) {
            return true;
        }
    }
}
