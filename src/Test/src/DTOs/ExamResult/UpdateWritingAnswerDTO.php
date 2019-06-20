<?php

declare(strict_types=1);

namespace Test\DTOs\ExamResult;

class UpdateWritingAnswerDTO extends UserAnswerDTO implements \JsonSerializable
{    
    /**
     * @var string
     */
    protected $writingContent;

    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret->writingContent = $this->getWritingContent();
          
        return $ret;
    }

    /**
     * Get the value of writingContent
     *
     * @return  string
     */ 
    public function getWritingContent()
    {
        return $this->writingContent;
    }

    /**
     * Set the value of writingContent
     *
     * @param  string  $writingContent
     *
     * @return  self
     */ 
    public function setWritingContent(string $writingContent)
    {
        $this->writingContent = $writingContent;

        return $this;
    }
}
