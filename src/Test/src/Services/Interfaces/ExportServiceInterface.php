<?php

declare(strict_types=1);

namespace Test\Services\Interfaces;

interface ExportServiceInterface
{
    public function exportCandidateExamResult($params, &$messages, &$outDTO);
}
