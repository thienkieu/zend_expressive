<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;

class ImportQuestionService implements ImportQuestionServiceInterface, HandlerInterface
{
    private $index = 1;
    private $type = 2;
    private $subType = 3;
    private $source = 4;
    private $fileName = 5;
    private $repeatTime = 6;
    private $content = 7;
    private $question = 8;
    private $correctAnswers = 9;
    private $answer1 = 10;
    private $answer2 = 11;
    private $answer3 = 12;
    private $answer4 = 13;
    private $answer5 = 143;

    public function isHandler($dto) {
        return true;
    }

    public function importQuestion($dtoObject, & $dto, & $messages) {
        if ( $xls = \SimpleXLSX::parse($dtoObject->file) ) {
            $rows = $xls->rows();
        } else {
            echo  \SimpleXLSX::parseError();
        }

    }

    protected function buildReadingQuestion($data){
        $readingQuestiong = new \Test\DTOs\Question\ReadingQuestionDTO();
        $readingQuestiong->setContent($data[$this->content]);
        $readingQuestiong->setType($data[$this->type]);
        $readingQuestiong->setSource($data[$this->source]);
        $readingQuestiong->setSubType($data[$this->subType]);
    }

    protected function buildListeningQuestion($data){

    }

    protected function buildWritingQuestion($data){

    }
    
}
