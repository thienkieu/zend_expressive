<?php

declare(strict_types=1);

namespace Test\Services;

class DoExamResultListeningService implements DoExamResultListeningServiceInterface
{
    public function listeningFinished($dto, & $messages) {

    }

    public function isAudioLoadingFinish($dto, & $messages) {

    }

    public function disconnectWhenListening($dto, & $messages) {

    }

    public function isAddMoreTimes($question, & $messages) {
        if ($question->getIsFinish() !== true && $question->getIsListening() === true && )
    }
}
