<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class WritingQuestionService extends QuestionService
{
    private $container;
    private $dm;
    private $options;
    private $translator= null;
    
    public function isHandler($param, $options = []){
        if ($options['document'] instanceof \Test\Documents\Test\WritingQuestionDocument){
            return true;
        }
        return false;
    }

    public function caculateMark(&$questionDocument) {}
    

}
