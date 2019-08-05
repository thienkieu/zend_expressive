<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\ExamResult;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToExamResultHasSectionTestDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {   
        if (isset($dtoObject->test)) {
            $sections = $dtoObject->test->sections;        
            if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] === DTOName::ExamResult && !empty($sections)) {
                return true;
            }
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO::class;
    }

    public function convert($jsonObject, $options = []) 
    {
        $dtoObject = new \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO();
        $dtoObject->setTitle($jsonObject->title);
        $dtoObject->setTime($jsonObject->time); 
        if (isset($jsonObject->id)) {
            $dtoObject->setId($jsonObject->id);    
        }  
        
        $date = \Infrastructure\CommonFunction::convertToDateTimeFormat($jsonObject->startDate);
        if ($date === false) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate("Field '%field%' cannot correct format, Please check it again.", ['%field%'=> 'startDate']);
            throw new \Infrastructure\Exception\ConvertException($message);            
        }        
        $dtoObject->setStartDate($date);
        $candidate = $this->convertor->convertToDTO($jsonObject->candidate, [\Config\AppConstant::DTOKey => \Test\DTOs\Exam\CandidateDTO::class]);
        $dtoObject->setCandidate($candidate);
        
        $jsonTest = $jsonObject->test;
        $test = $this->convertor->convertToDTO($jsonTest, [\Config\AppConstant::DTOKey => \Test\DTOs\Test\BaseTestDTO::class]);
        $dtoObject->setTest($test);

        return $dtoObject;            
    }
}