<?php

declare(strict_types=1);

namespace Test\DTOs\Question;

class ListeningQuestionDTO extends QuestionDTO implements \JsonSerializable
{
    protected $path;
    protected $repeat;
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
       
        $ret->repeat = $this->getRepeat();
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
}
