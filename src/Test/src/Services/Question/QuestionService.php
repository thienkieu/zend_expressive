<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Doctrine\Common\Collections\ArrayCollection;

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
    
    protected function getClassName($type, $subType) {
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
            case \Config\AppConstant::Other:
                return \Test\Documents\Question\VerbalQuestionDocument::class;
            break;
            case \Config\AppConstant::Verbal:
                return \Test\Documents\Question\VerbalQuestionDocument::class;
            break;
            case \Config\AppConstant::NonSub:
                return \Test\Documents\Question\NonSubQuestionDocument::class;
            break;
        }
        return \Test\Documents\Question\QuestionDocument::class;;
    }

    public function getQuestionWithSource($sourceId) {
        $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questionDocuments = $questionRepository->findBy(['source'=>$sourceId]);
        
        return $questionDocuments;
    }

    protected function correctQuestionMark(&$questionDTO, $numberSubQuestion) {
        $this->updateQuestionMark($questionDTO, $numberSubQuestion);
        
        $questionMark = $questionDTO->getMark();
        $markOfSubQuestion = $this->getSubQuestionMark($questionMark, $numberSubQuestion);
        $totalMarkOfSubQuestion = 0;
        
        $subQuestions = $questionDTO->getSubQuestions();  
        for ($i=0; $i < $numberSubQuestion ; $i++) {
            if ($i == $numberSubQuestion - 1 && !empty($questionMark)) {
                $markOfSubQuestion = $questionMark - $totalMarkOfSubQuestion;
            }
           
            $subQuestions[$i]->setMark($markOfSubQuestion);            

            $totalMarkOfSubQuestion += $markOfSubQuestion;
        }
    }

    protected function limitSubQuestion($questionDTO, $numberSubQuestion, $isKeepQuestionOrder = false, $isRandomAnswer = false) {
        if ($isKeepQuestionOrder === true) {
            return $this->limitSubQuestionKeepOrder($questionDTO, $numberSubQuestion, $isRandomAnswer);
        } 

        $subQuestions = $questionDTO->getSubQuestions();        
        $maxRand = count($subQuestions) - 1;
        $ret = [];

         for ($i=0; $i < $numberSubQuestion ; $i++) {
            $index = mt_rand(0, $maxRand);
            $qArray = array_splice($subQuestions, $index, 1);            
            $q = $qArray[0];  
            if ($isRandomAnswer === true) {
                $this->randomAnswer($q);
            }
            $ret[] = $q;

            $maxRand = $maxRand - 1;
        }

        return $ret;
    }

    protected function randomAnswer(& $subQuestionDTO) {
        $answers = $subQuestionDTO->getAnswers();
        $maxRand = count($answers) - 1;
        $numberAnswer = $maxRand + 1; 
        $ret = [];

        for ($i = 0; $i < $numberAnswer ; $i++) {
            $index = mt_rand(0, $maxRand);
            $qArray = array_splice($answers, $index, 1);            
            $w = $qArray[0];  
            
            $ret[] = $w;

            $maxRand = $maxRand - 1;
        }

        $subQuestionDTO->setAnswers($ret);
        
    }

    protected function limitSubQuestionKeepOrder($questionDTO, $numberSubQuestion, $isRandomAnswer) {
        $subQuestions = $questionDTO->getSubQuestions();        
        $maxRand = count($subQuestions) - 1;
        $ret = [];

        $questionsIndex =  range(0, $maxRand);
         for ($i=0; $i < $numberSubQuestion ; $i++) {
            $index = mt_rand(0, $maxRand);
            $questionIndex = array_splice($questionsIndex, $index, 1); 

            $qArray = array_splice($subQuestions, $index, 1);            
            $q = $qArray[0];  
            
            $qIndex = new \stdClass();
            $qIndex->index = $questionIndex[0];
            if ($isRandomAnswer === true) {
                $this->randomAnswer($q);
            }
            $qIndex->content = $q;
            $ret[] = $qIndex;

            $maxRand = $maxRand - 1;
        }
        
        usort($ret, function($a, $b) {
            if ($a->index==$b->index) return 0;
            return ($a->index < $b->index) ? -1 : 1;
        });

        $questions = [];
        foreach($ret as $q) {
            $questions[] = $q->content;
        }
        
        return $questions;
    }

    protected function updateQuestionMark(&$questionDTO, $numberSubQuestion) {
        $questionMark = $questionDTO->getMark();
        if (empty($questionMark)) $questionDTO->setMark(\Config\AppConstant::DefaultSubQuestionMark * $numberSubQuestion);
    }

    protected function getSubQuestionMark($questionMark, $numberSubQuestion) {
        if (empty($numberSubQuestion)) return $questionMark;
        if (empty($questionMark)) return \Config\AppConstant::DefaultSubQuestionMark;
        
        //TODO điểm lẽ => tổng điểm thành phần sẽ lớn hơn điểm của câu hỏi
        return round($questionMark / $numberSubQuestion, 2);
    }

    protected function generatePickupQuestion($questionInfo) {
        return $questionInfo->getQuestionInfo();
    }

    protected function generateRandomQuestion($citerial, $notInsources, $notInQuestions, $user, $keepCorrectAnswer = false) {
        $questionDTO = $citerial->getQuestionInfo();        
        $toClass = $this->getClassName($questionDTO->getRenderType(), $questionDTO->getSubType());
        $questionRepository = $this->dm->getRepository($toClass);
        
        $questionnotInsources = $notInsources;
        if (!$citerial->getQuestionInfo()->getIsDifferentSource()) {
            $questionnotInsources = [];
        }

        $platformId = $citerial->getQuestionInfo()->getPlatform();
        if (!$platformId) {
            throw new \Test\Exceptions\GenerateQuestionException($this->translator->translate('Platform is required.'));
        }
        
        $question = $questionRepository->generateRandomQuestion($questionDTO->getTypeId(), $questionDTO->getNumberSubQuestion(), $questionnotInsources, $notInQuestions, $toClass, $platformId, $user);
        if (!$question) {
            $generateQuestionCiterial = [
                '%type%' => $questionDTO->getType(),
                '%subType%' => $questionDTO->getSubType(),
                '%numberSubQuestion%' => $questionDTO->getNumberSubQuestion(),
                '%sources%' => implode(',', $notInsources)
            ];
            
            throw new \Test\Exceptions\GenerateQuestionException($this->translator->translate('Cannot generate question for test'));
        }
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $ret = $documentToDTOConvertor->convertToDTO($question, [\Config\AppConstant::ShowCorrectAnswer => $keepCorrectAnswer]);
        $ret->setMark($questionDTO->getMark());

        $numberSubQuestion = 0;
        $subQuestions = $this->limitSubQuestion($ret, $questionDTO->getNumberSubQuestion(), $questionDTO->getIsKeepQuestionOrder(), $questionDTO->getIsRandomAnswer());  
        $ret->setSubQuestions($subQuestions);
        $numberSubQuestion = count($subQuestions);
        
        $this->correctQuestionMark($ret, $numberSubQuestion);

        return $ret;
    }

    public function generateQuestion($citerial, $notInsources, $notInQuestions, $user, $keepCorrectAnswer = false) {
        
        if ($citerial->getGenerateFrom() !== \Config\AppConstant::Random) {
            $ret = $this->generatePickupQuestion($citerial);
            
            $numberSubQuestion = 0;
            if (method_exists($ret, 'getSubQuestions')) {
                $numberSubQuestion = count($ret->getSubQuestions());
            }

            $this->correctQuestionMark($ret, $numberSubQuestion);
            return $ret;
        }

        return $this->generateRandomQuestion($citerial, $notInsources, $notInQuestions, $user, $keepCorrectAnswer);
    }

    public function getQuestions($dto, $pageNumber, $itemPerPage, $isShowCorrectAnswer = false) {
        $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        
        $data = $questionRepository->getQuestionWithPagination($dto, $itemPerPage, $pageNumber);
        $questions = $data['questions'];
 
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        
        $dtos = [];
        foreach($questions as $document) {
            $dtoObject = $documentToDTOConvertor->convertToDTO($document, [\Config\AppConstant::ShowCorrectAnswer => $isShowCorrectAnswer]);
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
        $questionMark = $questionDocument->getMark();
        $subQuestionDocuments = $questionDocument->getSubQuestions();
        $numberSubQuestion = $subQuestionDocuments->count();
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
                $subQuestionMark = $subQuestion->getMark();
                if ($subQuestionMark) {
                    $candidateMark += $subQuestion->getMark();
                } else {
                    $candidateMark += ($questionMark / $numberSubQuestion);
                }
                
            }
        }

        return $numberCorrectSubQuestion;
    }

    protected function moveImageToQuestionFolder($content) {
        $mediaFolder = \Config\AppConstant::MediaQuestionFolder . \Config\AppConstant::DS.\Config\AppConstant::DS.date('Ymdhis');
        \Infrastructure\CommonFunction::createFolder($mediaFolder);

        $images = \Infrastructure\CommonFunction::extractImages($content);
        if ($images) {
            $baseImageName = [];
            foreach($images as $image) {
                $path =  \Infrastructure\CommonFunction::getRealPath($image);
                if ($path !== false && strpos($path, 'http://') === false) {
                    $realPathMediaFolder = \realpath($mediaFolder);

                    if (dirname(dirname($path)) === dirname($realPathMediaFolder)) {
                        \Infrastructure\CommonFunction::copyFileToFolder($path, $realPathMediaFolder);
                    } else {
                        \Infrastructure\CommonFunction::moveFileToFolder($path, $realPathMediaFolder);
                    }
                  
                    $mediaFolder.\basename($path);
                    $content = str_replace($image, \Config\AppConstant::HOST_REPLACE.'/'.$mediaFolder.'/'.\basename($path), $content);
                } 
            }
        }

        return $content;
    }

    public function createQuestion($dto, &$messages, & $outDTO) {
        $content = \Infrastructure\CommonFunction::replaceHost($dto->getContent());
        $content = $this->moveImageToQuestionFolder($content);
        $dto->setContent($content);
 
        $existingDocument = null;
        $id = $dto->getId();
        if (!empty($id)) {
            $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
            $existingDocument = $questionRepository->find($dto->getId());
        }

        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $questionDocument = $dtoToDocumentConvertor->convertToDocument($dto, $existingDocument ? [\Config\AppConstant::ExistingDocument => $existingDocument]: []);        
        
        $this->dm->persist($questionDocument);
        $this->dm->flush();

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $outDTO = $documentToDTOConvertor->convertToDTO($questionDocument, [\Config\AppConstant::ShowCorrectAnswer => true]);

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
        if ($questionDocument->getType()->getName() != $updateQuestionDTO->getSubType() || 
            $questionDocument->getSource()->getName() != $updateQuestionDTO->getSource() ||
            $questionDocument->getType()->getParentType()->getName() != $updateQuestionDTO->getType() || 
            (method_exists($questionDocument, 'getNumberSubQuestion') && $questionDocument->getNumberSubQuestion() > count($updateQuestionDTO->getSubQuestions()))) {
                return true;
            }
        return false;
    }

    public function editQuestion($dto, &$messages, & $outDTO) {
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
        $oldId = $questionDocument->getId();       
        $ok = $this->createQuestion($dto->setId($oldId), $messages, $outDTO);
        if ($ok) {
            $messages = [$this->translator->translate('The question has been update successfully!')];
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $outDTO = $documentToDTOConvertor->convertToDTO($questionDocument, [\Config\AppConstant::ShowCorrectAnswer => true]);
        }

        return true;
    }
    
}
