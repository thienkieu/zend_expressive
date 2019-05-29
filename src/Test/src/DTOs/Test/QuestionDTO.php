<?php

declare(strict_types=1);

namespace Test\DTOs\Test;

class SectionDTO
{

    protected $type;

    /**
     * @var PickupQuestionDTO[]
     */
    protected $pickupInfo;  

    /**
     * @var RandomQuestionDTO[]
     */
    protected $randomInfo;

    /**
     * Get the value of randomInfo
     *
     * @return  RandomQuestionDTO[]
     */ 
    public function getRandomInfo()
    {
        return $this->randomInfo;
    }

    /**
     * Set the value of randomInfo
     *
     * @param  RandomQuestionDTO[]  $randomInfo
     *
     * @return  self
     */ 
    public function setRandomInfo($randomInfo)
    {
        //convert to Question Type
        
        $this->randomInfo = $randomInfo;

        return $this;
    }

    /**
     * Get the value of pickupInfo
     *
     * @return  PickupQuestionDTO[]
     */ 
    public function getPickupInfo()
    {
        return $this->pickupInfo;
    }

    /**
     * Set the value of pickupInfo
     *
     * @param  PickupQuestionDTO[]  $pickupInfo
     *
     * @return  self
     */ 
    public function setPickupInfo($pickupInfo)
    {
        $this->pickupInfo = $pickupInfo;

        return $this;
    }
}
