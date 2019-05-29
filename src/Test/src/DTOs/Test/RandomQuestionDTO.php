<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class RandomQuestionDTO
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $isDifferentSource;
    
    /**
     * @var CriteriaRandomQuestionDTO[]
     */
    protected $info;
        
    /**
     * Get the value of info
     *
     * @return  CriteriaRandomQuestionDTO[]
     */ 
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set the value of info
     *
     * @param  CriteriaRandomQuestionDTO[]  $info
     *
     * @return  self
     */ 
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return  string
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */ 
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of isDifferentSource
     *
     * @return  bool
     */ 
    public function getIsDifferentSource()
    {
        return $this->isDifferentSource;
    }

    /**
     * Set the value of isDifferentSource
     *
     * @param  bool  $isDifferentSource
     *
     * @return  self
     */ 
    public function setIsDifferentSource(bool $isDifferentSource)
    {
        $this->isDifferentSource = $isDifferentSource;

        return $this;
    }
}
