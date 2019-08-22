<?php 

declare(strict_types=1);

namespace Test\BusinessDomain;

abstract class IsEffectToGenereateExam {
    
    protected $examService;
    
    /**
     * Class constructor.
     */
    public function __construct(ExamServiceInterface $examService = null)
    {
        $this->examService = $examService;
    }

    public function DoBusiness($questionDTO) {
        $this->checkQuestionExist($questionDTO);
        $this->checkEffectToExam($questionDTO);
    }

    protected abstract function checkQuestionExist($questionDTO);
    protected abstract function checkEffectToExam($questionDTO);
}