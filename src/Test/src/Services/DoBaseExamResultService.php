<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class DoBaseExamResultService implements DoExamResultServiceInterface, HandlerInterface
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
        return false;
    }

    public function updateAnswer($dto, & $messages) {
        try {
            $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
            $document = $testDocuments = $examResultRepository->getExamResult($dto->getExamId(), $dto->getCandidateId(), $dto->getQuestionId());
            
            if (!$document) {
                $messages[] = $this->translator->translate('There isnot exist question with', ['%questionId%' => $dto->getQuestionId()]);
                return false;
            }
            
            $this->updateSubQuestionAnswer($document, $dto);
            $this->dm->flush();

            return true;
        }catch(\Test\Exceptions\UpdateAnswerException $ex) {
            $messages[] = $ex->getMessage();
            return false;
        }
    }

    protected function updateSubQuestionAnswer(& $examResult, $dto) {        
    }

    protected function getQuestion(& $examResult, $dto) {
        $sections = $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            if ($section->getId() == $dto->getSectionId()) {
                $questions = $section->getQuestions();
                foreach ($questions as $question) {
                    if ($question->getId() == $dto->getQuestionId()) {
                        return $question->getQuestionInfo();                        
                    }
                }

                break;
            }
        }

        return null;
    }
}
