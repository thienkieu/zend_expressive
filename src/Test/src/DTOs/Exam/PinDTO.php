<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class PinDTO implements \JsonSerializable
{
    protected $pin; 
    
    public function jsonSerialize() {
        $ret = new \stdClass();
        $ret->pin = $this->getPin(); 

        return $ret;
    }

    /**
     * Get the value of pin
     */ 
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set the value of pin
     *
     * @return  self
     */ 
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }
}
