<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Test\Services\Question\QuestionServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;

class LogigearExamService extends ExamService
{
    public function isHandler($dto, $options = []){
        return true;
    }
   
    public function generateExamTest($test, & $messages, $keepCorrectAnswer = false, $options = []) {
        try {
            $testForDoExam = new \Test\DTOs\Test\TestWithSectionDTO();
            $sectionsForDoExam = [];
            
            $sections = [];
            if (method_exists($test, 'getSections')) {
                $sections = $test->getSections();
            }
            
            $sources = [];
            $questionIds = [];
            if (isset($options['questionId'])) {
                $questionIds = $options['questionId'];
            }

            $logigearTypeService = $this->container->get(TypeServiceInterface::class);
            foreach ($sections as $section) {
                $sectionMark = 0;
                $questionsForSection = [];
                $questions = $section->getQuestions(); 
                $isAllWriting = true;
                $hasInputQuestionMark = false;
                foreach ($questions as $question) {
                    $questionInfo = $question->getQuestionInfo();
                    
                    if (!isset($sources[$questionInfo->getTypeId()])) $sources[$questionInfo->getTypeId()] = [];
                    
                    $questionService = $this->container->build(QuestionServiceInterface::class, [\Config\AppConstant::DTOKey => $question]);
                    
                    $q = $questionService->generateQuestion($question, $sources[$questionInfo->getTypeId()], $questionIds, $test->getUser(), $keepCorrectAnswer);
                    $sources[$q->getTypeId()][] = $q->getSourceId();
                    $questionIds[] = $q->getId();
                    $sectionMark = $sectionMark + $q->getMark();

                    $testQuestionDTO = new \Test\DTOs\Test\QuestionDTO();
                    $testQuestionDTO->setId($q->getId());
                    $testQuestionDTO->setGenerateFrom(\Config\AppConstant::Pickup);
                    $testQuestionDTO->setQuestionInfo($q);
                    
                    if ($questionInfo->getMark()) {
                        $hasInputQuestionMark = true;
                    }

                    $questionsForSection[] = $testQuestionDTO;
                    if (!$logigearTypeService->isWritingQuestion($q)) {
                        $isAllWriting = false;
                    }
                }

                
                $sectionForDoExam = new \Test\DTOs\Test\SectionDTO();
                $sectionForDoExam->setName($section->getName());
                $sectionForDoExam->setDescription($section->getDescription());
                $sectionForDoExam->setQuestions($questionsForSection);    
                $sectionForDoExam->setIsOption($section->getIsOption());
                $sectionForDoExam->setRequiredQuestion($section->getRequiredQuestion());
                
                if ($isAllWriting && !$hasInputQuestionMark) {
                    $sectionForDoExam->setMark(\Config\AppConstant::DefaultWritingMark);
                }

                if (!$isAllWriting) {
                   // $sectionForDoExam->setMark($sectionMark);
                }

                
                
                $sectionsForDoExam[] = $sectionForDoExam;
            }

            
            $testForDoExam->setSections($sectionsForDoExam);
            $testForDoExam->setId($test->getId());
            $testForDoExam->setTitle($test->getTitle());
            $testForDoExam->setReferId($test->getReferId());            
            $testForDoExam->setUser($test->getUser());
            
            return $testForDoExam;
        } catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();            
            return false; 
        }
    }
}
