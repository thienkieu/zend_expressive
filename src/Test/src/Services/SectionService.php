<?php

declare(strict_types=1);

namespace Test\Services;

class SectionService implements SectionServiceInterface
{
    public function __construct() {

    }

    public function createSection(\Test\DTOS\SectionDTO $sectionDTO) {
        var_dump($sectionDTO);
    }
    
}
