<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Test\Services\Question\QuestionServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;

class EmptyTestExamService extends ExamService
{
    public function isHandler($dto, $options = []){
        if($dto instanceof \Test\DTOs\Test\BaseTestDTO)  {
            if (!method_exists($dto, 'getSections')) {
                return true;
            }
        }
        return false;
    }
   
    public function generateExamTest($test, & $messages, $keepCorrectAnswer = false, $options = []) {
        try {
            $testForDoExam = new \Test\DTOs\Test\TestWithSectionDTO();
            $sectionsForDoExam = [];
            
            $testForDoExam->setSections($sectionsForDoExam);
            $testForDoExam->setId($test->getId());
            $testForDoExam->setTitle($test->getTitle());
            $testForDoExam->setReferId($test->getReferId());
            
            return $testForDoExam;
        } catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();            
            return false; 
        }
    }
}
