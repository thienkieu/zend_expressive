<?php 

declare(strict_types=1);

namespace ODMAuth\Convertor\Adapter\DTOs;

use Infrastructure\Convertor\ToDTOAdapter;

class ToAssignUserPermissionDTOAdapter extends ToDTOAdapter {
    public function isHandleConvertToDTO($dtoObject, $options = []) : bool
    {
        if (isset($options[\Config\AppConstant::DTOKey])) {
            return true;
        }

        return false;
    }
    
    public function getDTOClass() {
        return \ODMAuth\DTOs\AssignUserPermissionDTO::class;
    }
}