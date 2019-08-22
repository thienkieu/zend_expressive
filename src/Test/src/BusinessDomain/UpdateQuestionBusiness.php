<?php 

declare(strict_types=1);

namespace Test\BusinessDomain;

abstract class UpdateQuestionBusiness {
    
    protected $questionService;
    protected $isEffectToGenerateExam;
    
    /**
     * Class constructor.
     */
    public function __construct(QuestionServiceInterface $questionService = null, IsEffectToGenerateExam $isEffectToGenerateExam)
    {
        $this->questionService = $questionService;
        $this->isEffectToGenerateExam = $isEffectToGenerateExam;
    }

    public function DoBusiness($questionDTO, $messages) {
        $this->checkQuestionExist($questionDTO);
        $isEffect = $this->isEffectToGenerateExam->DoBusiness($questionDTO);
        if ($isEffect) {
            $message = 'cannot generate question';
            return false;
        }
    }

    protected abstract function checkQuestionExist($questionDTO);
    protected abstract function checkEffectToExam($questionDTO);
}