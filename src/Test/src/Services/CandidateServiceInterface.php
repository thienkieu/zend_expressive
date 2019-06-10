<?php

declare(strict_types=1);

namespace Test\Services;


interface CandidateServiceInterface
{
    public function getCandidates(& $candiates, & $messages, $pageNumber, $itemPerPage);
}