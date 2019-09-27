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
            $isScored = false;
            $sectionMark = 0;
            $comments = [];
            foreach($questions as $question) {
                $questionInfo = $question->getQuestionInfo();
                if($questionInfo->getIsScored()) {
                    $isScored = true;
                }
                
                $comment = $questionInfo->getComment();
                if (!empty($comment)) {
                    $comments[] = $comment;
                }
                $candidateMark += $questionInfo->getCandidateMark();

                $subQuestionCount = 0;
                if ($questionInfo instanceof \Test\Documents\Test\HasSubQuestionDocument) {
                    $subQuestions = $questionInfo->getSubQuestions();
                    $subQuestionCount = $subQuestions->count();
                }
                
                if ($questionInfo instanceof \Test\Documents\Test\WritingQuestionDocument) {
                    $sectionMark = $questionInfo->getMark() ? $questionInfo->getMark(): $subQuestionCount * \Config\AppConstant::DefaultSubQuestionMark;
                } else {
                    $sectionMark += $questionInfo->getMark() ? $questionInfo->getMark(): $subQuestionCount * \Config\AppConstant::DefaultSubQuestionMark;
                }
                
            }

            $summary = new \Test\Documents\Exam\ExamResultSummaryDocument();
            $summary->setName($section->getName());
            $existingSectionCandidateMark = $section->getCandidateMark();
            if ($existingSectionCandidateMark || $existingSectionCandidateMark === 0) {
                $summary->setCandidateMark($existingSectionCandidateMark);
            } else {
                $summary->setCandidateMark($candidateMark);
            }
            
            if ($section->getComment()) {
                $comments[] = $section->getComment();
            }
            $summary->setComments($comments);
            
            
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