<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;
use Config\AppConstant;

class ToReadingDTOAdapter extends ToDTOAdapter {
    public function isHandle($request) : bool
    {
        $name = $request->getAttribute(AppConstant::RequestDTOName);
        $body = $request->getParsedBody();
        $type = $body->type;
        if ($name === DTOName::Section && $type === DTOName::Reading ) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\ReadingSectionDTO::class;
    }
    
}