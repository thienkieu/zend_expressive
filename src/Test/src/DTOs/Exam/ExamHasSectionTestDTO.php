<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class ExamHasSectionTestDTO extends ExamDTO
{
    /**
     * Add the value of candidate
     *
     * @param  \Test\DTOs\Test\TestWithSectionDTO $test
     *
     * @return  self
     */  
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }
}
