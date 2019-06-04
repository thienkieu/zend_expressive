<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToListeningDTOAdapter extends ToDTOAdapter {
    public function isHandle($dtoObject, $name) : bool
    {
        $type = isset($dtoObject->type) ? $dtoObject->type: '';
        if ($name === DTOName::QuestionDTO && $type === DTOName::Listening ) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Question\ListeningQuestionDTO::class;
    }
}