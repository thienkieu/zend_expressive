<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class WritingQuestionService implements QuestionServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator= null;
    
    public function isHandler($param, $options = []){
        return true;
    }

    public function caculateMark(&$questionDocument, $markPerQuestion) {}
    

}
