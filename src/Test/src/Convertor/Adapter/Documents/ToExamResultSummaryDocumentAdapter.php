<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ToExamResultSummaryDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
    protected $container;
    protected $convertor;

    /**
     * Class constructor.
     */
    public function __construct($container, $convertor)
    {
        $this->container = $container;
        $this->convertor = $convertor;
    }
    
    public function isHandleConvertDTOToDocument($dtoObject, $options = []) : bool
    {
        if ($dtoObject instanceof \Test\Documents\Exam\ExamResultSummaryDocument) {
            return true;
        }
        
        return false;
    }
    
    public function convert($examResultDocument, $options = []) 
    {   
        $summaries = new ArrayCollection();      
        $sections = $examResultDocument->getTest()->getSections();
        foreach($sections as $section) {
            $questions = $section->getQuestions();
            $mark = 0;
            foreach($questions as $question) {
                $mark += $question->getQuestionInfo()->getCandidateMark();
            }

            $summary = new \Test\Documents\Exam\ExamResultSummaryDocument();
            $summary->setName($section->getName());
            $summary->setMark($mark);

            $summaries->add($summary);
        }
        
        return $summaries;
    }
}