<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class DoExamResultService implements DoExamResultServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');    
        $this->translator = $this->container->get(\Config\AppConstant::Translator);    
    }

    public function isHandler($dto, $options = []){
        return true;
    }

    public function updateRepeatTimes($dto, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $testDocuments = $examResultRepository->updateAnwser($dto->getExamId(), $dto->getCandidateId(), $dto->getQuestionId(), $dto->getSubQuestionId(), $dto->getAnswers());
        
    }

    protected function reduceRepeatTimes(& $examResult, $sectionId, $questionId,  $questionInfoId, $subQuestionId, $userChoiceAnswers) {
        $sections = $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            if ($section->getId() == $sectionId) {
                $questions = $section->getQuestions();
                foreach ($questions as $question) {
                    if ($question->getId() == $questionId) {
                        $questionInfo = $question->getQuestionInfo();
                        $subQuestions = $question->getQuestionInfo()->getSubQuestions();

                        foreach ($subQuestions as $subQuestion) {
                            if ($subQuestion->getId() == $subQuestionId) {
                                $answers = $subQuestion->getAnswers();
                                foreach ($answers as $answer) {
                                    $answer->setIsUserChoice($this->getAnswerStatus($answer->getId(), $userChoiceAnswers));
                                }
                                break;
                            }
                        }

                        break;
                    }
                }

                break;
            }
        }
        
        return $examResult;
    }


    public function updateWritingAnswer($dto, & $messages) {

    }

    public function updateAnswer($dto, & $messages) {
        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $document = $testDocuments = $examResultRepository->updateAnwser($dto->getExamId(), $dto->getCandidateId(), $dto->getQuestionId(), $dto->getSubQuestionId(), $dto->getAnswers());
        $this->updateSubQuestionAnswer($document, $dto->getSectionId(), $dto->getQuestionId(), $dto->getSubQuestionId(), $dto->getAnswers());
        $this->dm->flush();
        if (!$document) {
            $messages[] = $this->translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
            return false;
        }
        
        return true;
    }

    protected function updateSubQuestionAnswer(& $examResult, $sectionId, $questionId, $subQuestionId, $userChoiceAnswers) {
        $sections = $examResult->getTest()->getSections();
        foreach ($sections as $section) {
            if ($section->getId() == $sectionId) {
                $questions = $section->getQuestions();
                foreach ($questions as $question) {
                    if ($question->getId() == $questionId) {
                        $questionInfo = $question->getQuestionInfo();
                        $subQuestions = $question->getQuestionInfo()->getSubQuestions();

                        foreach ($subQuestions as $subQuestion) {
                            if ($subQuestion->getId() == $subQuestionId) {
                                $answers = $subQuestion->getAnswers();
                                foreach ($answers as $answer) {
                                    $answer->setIsUserChoice($this->getAnswerStatus($answer->getId(), $userChoiceAnswers));
                                }
                                break;
                            }
                        }

                        break;
                    }
                }

                break;
            }
        }
        
        return $examResult;
    }

    protected function getAnswerStatus($answerId, $userChoices) {
        foreach ($userChoices as $userChoice) {
            if ($userChoice->getId() == $answerId) {
                return $userChoice->getIsUserChoice();
            }
        }
    }

}
