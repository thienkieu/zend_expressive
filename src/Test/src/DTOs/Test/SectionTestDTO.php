<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class SectionTestDTO extends BaseTestDTO implements \JsonSerializable
{
    protected $sections;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->title = $this->getTitle();
        $ret->sections = $this->getSections();       
        return $ret;
    }

}
