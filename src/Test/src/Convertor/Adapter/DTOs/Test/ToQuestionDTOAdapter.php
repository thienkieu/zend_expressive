<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToQuestionDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if (isset($options[\Config\AppConstant::DTOKey]) && $options[\Config\AppConstant::DTOKey] ===  \Test\DTOs\Test\QuestionDTO::class) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\QuestionDTO::class;
    }

    public function convert($jsonObject, $options = []) 
    {  
        $dto = new \Test\DTOs\Test\QuestionDTO();
        $dto->setGenerateFrom($jsonObject->generateFrom);
        switch($jsonObject->generateFrom) {
            case \Config\AppConstant::Pickup: 
                $question = $this->getPickupQuestion($jsonObject);
                $dto->setQuestionInfo($question);
            break;
            case \Config\AppConstant::Random:
                $question = $this->getRandomQuestion($jsonObject);
                $dto->setQuestionInfo($question);
            break;
            default:
                return null;
            break;
        }
        
        return $dto;   
    }

    protected function getPickupQuestion($jsonObject) {
        return $questionInfo = $question = $this->convertor->convertToDTO($jsonObject, [\Config\AppConstant::DTOKey => \Test\DTOs\Question\QuestionDTO::class]);
    }

    protected function getRandomQuestion($jsonObject) {
        $dto = new \Test\DTOs\Test\RandomQuestionDTO();
        $dto->setNumberSubQuestion($jsonObject->numberSubQuestion);
        $dto->setType($jsonObject->type);
        $dto->setSubType($jsonObject->subType);
        $dto->setIsDifferentSource($jsonObject->isDifferentSource);

        return $dto;
    }
}