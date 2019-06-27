<?php

declare(strict_types=1);

namespace Test\Services;


interface PinServiceInterface
{
    public function refreshPin($examId, $candiateId, & $outNewPin, & $messages);
    public function showPinInfo($dto, & $results, & $messages);
    public function inValidPin($examId, $candidateId);
}
