<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use is_numeric;
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
        $examResultService = $this->container->get(\Test\Services\DoExamResultServiceInterface::class);
        foreach($sections as $section) {
            
            
            $questions = $section->getQuestions();
            $candidateMark = 0;
            $isScored = $examResultService->isScoredSection($section);
            $sectionMark = 0;
            $comments = [];
            $optionQuestionMark =  0;
            $totalQuestionInSection = 0;
            foreach($questions as $question) {
                $questionInfo = $question->getQuestionInfo();
                $totalQuestionInSection += 1;
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
                
                $m = 0;
                if ($questionInfo instanceof \Test\Documents\Test\WritingQuestionDocument) {
                    $m += $questionInfo->getMark() ? $questionInfo->getMark():  \Config\AppConstant::DefaultWritingMark;
                }else if ($questionInfo instanceof \Test\Documents\Test\NonSubQuestionDocument) {
                    $m += $questionInfo->getMark() ? $questionInfo->getMark(): \Config\AppConstant::DefaultSubQuestionMark;
                }else {
                    $m += $questionInfo->getMark() ? $questionInfo->getMark(): $subQuestionCount * \Config\AppConstant::DefaultSubQuestionMark;
                }
                
                $sectionMark += $m;
                $optionQuestionMark = $m;
            }

            $isOptionSection =  $section->getIsOption();
            $requiredNumberQuestion = $section->getRequiredQuestion();

            if ($isOptionSection === true) {
                if (!$requiredNumberQuestion) {
                    $requiredNumberQuestion = $totalQuestionInSection;
                }
                $sectionMark = $optionQuestionMark * $requiredNumberQuestion;
                
            }

            $summary = new \Test\Documents\Exam\ExamResultSummaryDocument();
            $summary->setName($section->getName());
            $existingSectionCandidateMark = $section->getCandidateMark();
            if (!is_numeric($existingSectionCandidateMark) && $existingSectionCandidateMark !== 0 && $existingSectionCandidateMark !== "0") {
                $summary->setCandidateMark($candidateMark);
            } else {
                $summary->setCandidateMark($existingSectionCandidateMark);
                $summary->setIsToeic($section->getIsToeic());
                $summary->setToeicExpirationDate($section->getToeicExpirationDate());
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