<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs\Test;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToQuestionDTOAdapter extends ToDTOAdapter {
    public function isHandle($request) : bool
    {
        $name = $request->getAttribute(AppConstant::RequestDTOName);
        $body = $request->getParsedBody();
        $type = isset($body->sections) ? $body->sections: '';
        if ($name === DTOName::Test && !empty($type)) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\QuestionDTO::class;
    }

    public function convert($jsonObject) 
    {  
        $dto = new \Test\DTOs\Test\QuestionDTO();
        $dto->setGenerateFrom($jsonObject->generateFrom);
        switch($jsonObject->generateFrom) {
            case 'pickup': 
                $question = $this->getPickupQuestion($jsonObject);
                $dto->setQuestionInfo($question);
            break;
            case 'random':
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
        switch($jsonObject->type) {
            case 'listening':
                $adapter = new \Test\Convertor\Adapter\DTOs\ToListeningDTOAdapter();
                return $adapter->convert($jsonObject);
            break;
            case 'reading':
                $adapter = new \Test\Convertor\Adapter\DTOs\ToReadingDTOAdapter();
                return $adapter->convert($jsonObject);
            break;
            case 'writing':
                $adapter = new \Test\Convertor\Adapter\DTOs\ToWritingDTOAdapter();
                return $adapter->convert($jsonObject);
            break;
            default:
                return null;
            break;
        }
    }

    protected function getRandomQuestion($jsonObject) {
        $dto = new \Test\DTOs\Test\RandomQuestionDTO();
        $dto->setNumberQuestion($jsonObject->numberQuestion);
        $dto->setType($jsonObject->type);
        $dto->setSubType($jsonObject->subType);
        $dto->setIsDifferentSource($jsonObject->isDifferentSource);

        return $dto;
    }
}