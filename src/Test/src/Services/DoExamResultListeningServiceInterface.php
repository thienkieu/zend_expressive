<?php

declare(strict_types=1);

namespace Test\Services;

interface DoExamResultListeningServiceInterface
{
    public function listeningFinished($dto, & $messages);
    public function isAudioLoadingFinish($dto, & $messages);
    public function disconnectWhenListening($dto, & $messages);
}
