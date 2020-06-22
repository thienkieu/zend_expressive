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
        $dto->setTypeId($jsonObject->typeId);
        $dto->setSubType($jsonObject->subType);
        $dto->setPlatform($jsonObject->platform);
        $dto->setRenderType($jsonObject->renderType);
        
        if (empty($jsonObject->typeId)) {
            $typeService = $this->container->get(\Test\Services\Interfaces\TypeServiceInterface::class);
            $typeDTO = $typeService->getTypeByName($jsonObject->type);
            $dto->setTypeId($typeDTO->getId());
        }
        
        $dto->setIsDifferentSource($jsonObject->isDifferentSource);
        if (isset($jsonObject->isKeepQuestionOrder)) {
            $dto->setIsKeepQuestionOrder(!!$jsonObject->isKeepQuestionOrder);
        } else {
            $dto->setIsKeepQuestionOrder(false);
        }

        if (isset($jsonObject->randomAnswer)) {
            $dto->setIsRandomAnswer((!!$jsonObject->randomAnswer));
        } else {
            $dto->setIsRandomAnswer(false);
        }
        
        if (isset($jsonObject->mark)) $dto->setMark($jsonObject->mark);
        
        return $dto;
    }
}