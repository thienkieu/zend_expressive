<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;
use Test\Enum\DTOName;

class ToWritingDTOAdapter extends ToDTOAdapter {
    public function isHandle($request) : bool
    {
        $name = $request->getAttribute('dto_name');
        if ($name === DTOName::Writing) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \Test\DTOs\WritingSectionDTO::class;
    }
}