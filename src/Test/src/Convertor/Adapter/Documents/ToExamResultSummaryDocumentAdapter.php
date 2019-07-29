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
    
    public function getInputMarkType($questions) {
        $ret = \Config\AppConstant::MarkInputTypeAuto;
        foreach($questions as $question) {
            if ($question->getQuestionInfo()->getType()->getIsManualScored()) {
                return \Config\AppConstant::MarkInputTypeManual;
            }
        }

        return $ret;
    }

    public function convert($examResultDocument, $options = []) 
    {   
        $summaries = new ArrayCollection();      
        $sections = $examResultDocument->getTest()->getSections();
        foreach($sections as $section) {
            $questions = $section->getQuestions();
            $candidateMark = 0;
            $isScored = true;
            $sectionMark = 0;
            foreach($questions as $question) {
                $questionInfo = $question->getQuestionInfo();
                $isScored = $questionInfo->getIsScored();
                
                $candidateMark += $questionInfo->getCandidateMark();
                $sectionMark += $questionInfo->getMark();
            }

            $summary = new \Test\Documents\Exam\ExamResultSummaryDocument();
            $summary->setName($section->getName());
            $summary->setCandidateMark($candidateMark);
            
            if (!empty($section->getMark())) {
                $summary->setMark($section->getMark());
            } else {
                $summary->setMark($sectionMark);
            }
            
            $summary->setType($this->getInputMarkType($questions));
            $summary->setIsScored($isScored);

            $summaries->add($summary);
        }
        
        return $summaries;
    }
}