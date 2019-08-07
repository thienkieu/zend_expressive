<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class TestTemplateDTO extends BaseTestDTO implements \JsonSerializable
{
    /**
     * @var SectionDTO[]
     */
    protected $sections;
    
    protected $isDefault;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->referId = $this->getReferId() ? $this->getReferId(): '';
        $ret->isDefault = $this->getIsDefault();
        $ret->title = $this->getTitle();
        $ret->sections = $this->getSections();       
        return $ret;
    }

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->sections = [];
    }


    /**
     * Get the value of sections
     *
     * @return  SectionDTO[]
     */ 
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Set the value of sections
     *
     * @param  SectionDTO[]  $sections
     *
     * @return  self
     */ 
    public function setSections($sections)
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * Add the value of sections
     *
     * @param  SectionDTO  $section
     *
     * @return  self
     */ 
    public function addSection($section)
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * Get the value of isDefault
     */ 
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set the value of isDefault
     *
     * @return  self
     */ 
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}