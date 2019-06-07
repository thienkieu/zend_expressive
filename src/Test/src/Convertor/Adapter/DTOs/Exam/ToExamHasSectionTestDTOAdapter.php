<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Exam;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToExamHasSectionTestDTOAdapter extends ToDTOAdapter {
    public function isHandle($dtoObject, $name) : bool
    {   
        if (isset($dtoObject->test)) {
            $sections = $dtoObject->test->sections;        
            if ($name === DTOName::Exam && !empty($sections)) {
                return true;
            }
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Exam\ExamHasSectionTestDTO::class;
    }

    public function convert($jsonObject) 
    {
        $dtoObject = new \Test\DTOs\Exam\ExamHasSectionTestDTO();
        $dtoObject->setTitle($jsonObject->title);
        $dtoObject->setTime($jsonObject->time);
        $dtoObject->setStartDate($jsonObject->startDate);
        
        $candidateDTOs = [];
        foreach ($jsonObject->candidates as $jsonCandiate) {
            $candidate = $this->convertor->convertToDTO($jsonCandiate, \Test\DTOs\Exam\CandidateDTO::class);
            $candidateDTOs[] = $candidate;
        }

        $dtoObject->setCandidates($candidateDTOs);
        
        $jsonTest = $jsonObject->test;
        $test = $this->convertor->convertToDTO($jsonTest, \Test\DTOs\Test\BaseTestDTO::class);
        $dtoObject->setTest($test);

        return $dtoObject;            
    }
}