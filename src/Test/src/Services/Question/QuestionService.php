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
        
        for ($i=0; $i < $numberSubQuestion ; $i++) {
            $index = mt_rand(0, $maxRand);
            $q = array_splice($subQuestions, $index, 1);
            $ret[] = $q[0];
            $maxRand = $maxRand - 1;
        }
        
        return $ret;
    }

    public function generateQuestion($citerial, $notInsources, $notInQuestions) {
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
        $ret = $documentToDTOConvertor->convertToDTO($question);
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
        $questionDocument->setCandidateMark($candidateMark);
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
    

}
