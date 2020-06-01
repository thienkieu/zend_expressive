<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class ListeningQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    protected $path;
    protected $repeat;
    protected $duration;
    private $numberCorrectSubQuestion;
    
    /**
     * Get the value of repeat
     */ 
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * Set the value of repeat
     *
     * @return  self
     */ 
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;

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
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret->path = $this->getPath();
        $ret->repeat = $this->getRepeat();
        $ret->duration = $this->getDuration();
        $ret->subQuestions = $this->getSubQuestions();
        $ret->numberCorrectSubQuestion = $this->getNumberCorrectSubQuestion() ? $this->getNumberCorrectSubQuestion() : 0;
       
        return $ret;
    }

    /**
     * Get the value of numberCorrectSubQuestion
     */ 
    public function getNumberCorrectSubQuestion()
    {
        return $this->numberCorrectSubQuestion;
    }

    /**
     * Set the value of numberCorrectSubQuestion
     *
     * @return  self
     */ 
    public function setNumberCorrectSubQuestion($numberCorrectSubQuestion)
    {
        $this->numberCorrectSubQuestion = $numberCorrectSubQuestion;

        return $this;
    }

    /**
     * Get the value of duration
     */ 
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     *
     * @return  self
     */ 
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }
}
