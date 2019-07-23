<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class QuestionService implements QuestionServiceInterface, HandlerInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $translator= null;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
    }

    public function isHandler($param, $options = []){
        return true;
    }
    
    protected function getClassName($type) {
        switch($type) {
            case \Config\AppConstant::Reading:
                return \Test\Documents\Question\ReadingQuestionDocument::class;
            break;
            case \Config\AppConstant::Listening:
                return \Test\Documents\Question\ListeningQuestionDocument::class;
            break;
            case \Config\AppConstant::Writing:
                return \Test\Documents\Question\WritingQuestionDocument::class;
            break;
        }
        return \Test\Documents\Question\QuestionDocument::class;;
    }

    public function getQuestionWithSource($sourceId) {
        $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questionDocuments = $questionRepository->findBy(['source'=>$sourceId]);
        
        return $questionDocuments;
    }

    protected function limitSubQuestion($questionDTO, $numberSubQuestion) {
        $subQuestions = $questionDTO->getSubQuestions();        
        $maxRand = count($subQuestions) - 1;
        $ret = [];

        $questionMark = $questionDTO->getMark();
        $markOfSubQuestion = $this->getSubQuestionMark($questionMark, $numberSubQuestion);
        $totalMarkOfSubQuestion = 0;
        for ($i=0; $i < $numberSubQuestion ; $i++) {
            if ($i == $numberSubQuestion - 1 && !empty($questionMark)) {
                $markOfSubQuestion = $questionMark - $totalMarkOfSubQuestion;
            }

            $index = mt_rand(0, $maxRand);
            $qArray = array_splice($subQuestions, $index, 1);            
            $q = $qArray[0];
            $q->setMark($markOfSubQuestion);            
            $ret[] = $q;

            $maxRand = $maxRand - 1;
            $totalMarkOfSubQuestion += $markOfSubQuestion;
        }
        
        return $ret;
    }

    protected function getSubQuestionMark($questionMark, $numberSubQuestion) {
        if (empty($questionMark)) return \Config\AppConstant::DefaultSubQuestionMark;
        
        //TODO điểm lẽ => tổng điểm thành phần sẽ lớn hơn điểm của câu hỏi
        return round($questionMark / $numberSubQuestion, 2);
    }

    public function generateQuestion($citerial, $notInsources, $notInQuestions, $keepCorrectAnswer = false) {
        if ($citerial->getGenerateFrom() !== \Config\AppConstant::Random) {
            return $citerial;
        }

        $questionDTO = $citerial->getQuestionInfo();        
        $toClass = $this->getClassName($questionDTO->getType());
        $questionRepository = $this->dm->getRepository($toClass);
        
        if (!$citerial->getQuestionInfo()->getIsDifferentSource()) {
            $notInsources = [];
        }
       
        $question = $questionRepository->generateRandomQuestion($questionDTO->getType(), $questionDTO->getSubType(), $questionDTO->getNumberSubQuestion(), $notInsources, $notInQuestions, $toClass);
        if (!$question) {
            $generateQuestionCiterial = [
                '%type%' => $questionDTO->getType(),
                '%subType%' => $questionDTO->getSubType(),
                '%numberSubQuestion%' => $questionDTO->getNumberSubQuestion(),
                '%sources%' => implode(',', $notInsources)
            ];
            
            throw new \Test\Exceptions\GenerateQuestionException($this->translator->translate('Cannot generate question with citerials: [type => %type%, subType => %subType%, numberSubQuestion => %numberSubQuestion%, sources=>%sources%]', $generateQuestionCiterial));
        }
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $ret = $documentToDTOConvertor->convertToDTO($question, [\Config\AppConstant::ShowCorrectAnswer => $keepCorrectAnswer]);
        $ret->setMark($questionDTO->getMark());
        if (!($ret instanceof \Test\DTOs\Question\WritingQuestionDTO)) {
            $subQuestions = $this->limitSubQuestion($ret, $questionDTO->getNumberSubQuestion());
            $ret->setSubQuestions($subQuestions);
        }
        return $ret;
    }

    public function getQuestions($dto, $pageNumber, $itemPerPage) {
        $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $data = $questionRepository->getQuestionWithPagination($dto, $itemPerPage, $pageNumber);
        $questions = $data['questions'];

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $dtos = [];
        foreach($questions as $document) {
            $dtoObject = $documentToDTOConvertor->convertToDTO($document);
            $dtos[] = $dtoObject;
        }
       
        return [
            'totalDocument' => $data['totalDocument'],
            'questions' => $dtos 
        ];
    }

    public function caculateMark(&$questionDocument) {
        $candidateMark = 0;
        $numberCorrectSubQuestion = $this->getNumberCorrectSubQuestion($questionDocument, $candidateMark);
        $questionDocument->setNumberCorrectSubQuestion($numberCorrectSubQuestion);
        $this->setCandidateMark($questionDocument, $candidateMark);
    }

    public function setCandidateMark(&$questionDocument, $mark) {
        $questionDocument->setCandidateMark($mark);
        $questionDocument->setIsScored(true);
    }

    protected function getNumberCorrectSubQuestion($questionDocument, & $candidateMark) {
        $numberCorrectSubQuestion = 0;

        $subQuestionDocuments = $questionDocument->getSubQuestions();
        foreach ($subQuestionDocuments as $subQuestion) {
            $answers = $subQuestion->getAnswers();
            $isCorrect = true;
            foreach ($answers as $answer) {
                $isCorrectValue = $answer->getIsCorrect();
                $isUserChoice = $answer->getIsUserChoice();
                if ( 
                    (!empty($isCorrectValue) && empty($isUserChoice)) || 
                    (empty($isCorrectValue) && !empty($isUserChoice))
                ) {
                    $isCorrect = false;
                    break;
                }

            }
            
            if ($isCorrect) {
                $numberCorrectSubQuestion += 1;
                $candidateMark += $subQuestion->getMark();
            }
        }

        return $numberCorrectSubQuestion;
    }

    public function createQuestion($dto, &$messages) {
        $content = \Infrastructure\CommonFunction::replaceHost($dto->getContent());
        $dto->setContent($content);

        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $questionDocument = $dtoToDocumentConvertor->convertToDocument($dto);        
        //echo '<pre>'.print_r($questionDocument->getSource(), true).'</pre>'; die;
        $this->dm->persist($questionDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('The question have been created successfully!');
        return true;
    }

    public function deleteQuestion($id, &$messages) {
        $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questionDocument = $questionRepository->find($id);
        if (!$questionDocument) {
            $messages[] = $this->translator->translate('The question doesnot exist, Please check it again.');
            return false;
        }

        $examService  = $this->container->get(\Test\Services\ExamServiceInterface::class);
        $examDTONotStarted = $examService->getExamNotStarted();
        $options = [
            'questionId' => [$id]
        ];

        $isAbleGenerateExam = true;
        foreach($examDTONotStarted as $exam) {
            $test = $exam->getTest();
            $isAbleGenerateExam = $examService->generateExamTest($exam->getTest(), $m, false, $options);
            if ($isAbleGenerateExam === false) {
                $messages[] = $this->translator->translate('Cannot generate a test for exam %examName%', ['%examName%'=> $exam->getTitle()]);
                break;
            }
        }

        if (!$isAbleGenerateExam) {
            return false;
        }

        $this->dm->remove($questionDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('The question has been deleted successfully!');
        return true;
    }

    protected function hasEffectToGenerateExamTest($questionDocument, $updateQuestionDTO) {
        if ($questionDocument->getType() != $updateQuestionDTO->getType() || 
            $questionDocument->getSource()->getName() != $updateQuestionDTO->getSource() ||
            $questionDocument->getSubType() != $updateQuestionDTO->getSubType() || 
            $questionDocument->getNumberSubQuestion() > count($updateQuestionDTO->getSubQuestions())) {
                return true;
            }
        return false;
    }

    public function editQuestion($dto, &$messages) {
        $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questionDocument = $questionRepository->find($dto->getId());
        if (!$questionDocument) {
            $messages[] = $this->translator->translate('The question doesnot exist, Please check it again.');
            return false;
        }

        $hasEffectToGenerateTestExam = $this->hasEffectToGenerateExamTest($questionDocument, $dto);
        if ($hasEffectToGenerateTestExam) {
            $examService  = $this->container->get(\Test\Services\ExamServiceInterface::class);
            $examDTONotStarted = $examService->getExamNotStarted();
            $options = [
                'questionId' => [$dto->getId()]
            ];

            $isAbleGenerateExam = true;
            foreach($examDTONotStarted as $exam) {
                $test = $exam->getTest();
                $isAbleGenerateExam = $examService->generateExamTest($exam->getTest(), $m, false, $options);
                if ($isAbleGenerateExam === false) {
                    $messages[] = $this->translator->translate('Cannot generate a test for exam %examName%', ['%examName%'=> $exam->getTitle()]);
                    break;
                }
            }

            if (!$isAbleGenerateExam) {
                return false;
            }

        }

        $this->dm->remove($questionDocument);
        $ok = $this->createQuestion($dto, $messages);
        if ($ok) {
            $messages = [$this->translator->translate('The question has been update successfully!')];
        }

        return true;
    }
    
}
