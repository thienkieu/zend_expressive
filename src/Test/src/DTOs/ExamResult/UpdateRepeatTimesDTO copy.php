<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class UpdateRepeatTimesDTO extends UserAnswerDTO implements \JsonSerializable
{    
    /**
     * @var int
     */
    protected $repeatTimesRemain;

    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret->repeatTimesRemain = $this->getRepeatTimesRemain();
          
        return $ret;
    }

    /**
     * Get the value of repeatTimesRemain
     *
     * @return  int
     */ 
    public function getRepeatTimesRemain()
    {
        return $this->repeatTimesRemain;
    }

    /**
     * Set the value of repeatTimesRemain
     *
     * @param  int  $repeatTimesRemain
     *
     * @return  self
     */ 
    public function setRepeatTimesRemain(int $repeatTimesRemain)
    {
        $this->repeatTimesRemain = $repeatTimesRemain;

        return $this;
    }
}
