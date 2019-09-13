<?php

declare(strict_types=1);

namespace ODMAuth\Services\Interfaces;

use Zend\Log\Logger;

Interface DoExamAuthorizationServiceInterface
{
    public function setCandidateInfo($candidateInfo);
    public function getCandidateInfo();
}
