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

    protected $path;

    protected $platform;
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->id = $this->getId();
        $ret->referId = $this->getReferId() ? $this->getReferId(): '';
        $ret->isDefault = $this->getIsDefault();
        $ret->title = $this->getTitle();
        $ret->sections = $this->getSections(); 
        $ret->path = $this->getPath(); 
        $ret->platform = $this->getPlatform();     
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

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of platform
     */ 
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set the value of platform
     *
     * @return  self
     */ 
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }
}
