<?php

declare(strict_types=1);

namespace Test\Services;

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

    public function generateQuestion($citerial, $notInsources) {
        if ($citerial->getGenerateFrom() !== \Config\AppConstant::Random) {
            return $citerial;
        }

        $questionDTO = $citerial->getQuestionInfo();
        $toClass = $this->getClassName($questionDTO->getType());
        $questionRepository = $this->dm->getRepository($toClass);
        
        $question = $questionRepository->generateRandomQuestion($questionDTO->getType(), $questionDTO->getSubType(), $questionDTO->getNumberSubQuestion(), $notInsources, $toClass);
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
}
