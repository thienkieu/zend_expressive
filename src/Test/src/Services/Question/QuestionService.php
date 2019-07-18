<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class QuestionService implements QuestionServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator= null;

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
        $builder = $this->dm->createQueryBuilder([
            \Test\Documents\Question\ReadingQuestionDocument::class, 
            \Test\Documents\Question\ListeningQuestionDocument::class,
            \Test\Documents\Question\WritingQuestionDocument::class
        ]);

       
        $builder = $builder->field('content')->equals(new \MongoRegex('/.*'.$dto->content.'.*/i'))
                           ->field('type')->equals(new \MongoRegex('/.*'.$dto->type.'.*/i'));
        $totalDocument = $builder->getQuery()->execute()->count();
        
        $data = $builder->limit($itemPerPage)
                        ->skip($itemPerPage*($pageNumber-1))
                        ->getQuery()
                        ->execute();
        
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $dtos = [];
        foreach($data as $document) {
            $dtoObject = $documentToDTOConvertor->convertToDTO($document);
            $dtos[] = $dtoObject;
        }
       
        return [
            'totalDocument' => $totalDocument,
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
        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $questionDocument = $dtoToDocumentConvertor->convertToDocument($dto);
        $this->dm->persist($questionDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('The question have been created successfully!');
        return true;
    }
    

}
