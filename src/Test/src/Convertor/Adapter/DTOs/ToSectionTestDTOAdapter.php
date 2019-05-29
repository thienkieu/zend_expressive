<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Zend\Hydrator\ReflectionHydrator;
use Infrastructure\Convertor\ToDTOAdapter;

use Test\Enum\DTOName;
use Config\AppConstant;

class ToSectionTestDTOAdapter extends ToDTOAdapter {
    public function isHandle($request) : bool
    {
        $name = $request->getAttribute(AppConstant::RequestDTOName);
        $body = $request->getParsedBody();
        $type = $body->sections;
        if ($name === DTOName::Test && !empty($type)) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\Test\ToSectionTestDTO::class;
    }
}