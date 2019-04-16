<?php

declare(strict_types=1);

namespace Test\Services;


interface SectionServiceInterface
{
    public function createSection(\Test\DTOS\SectionDTO $sectionDTO);
}
