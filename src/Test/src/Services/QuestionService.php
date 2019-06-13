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

    public function isHandler($param){
        return true;
    }
    
    protected function getRepositoryByType($type) {
        switch($type) {
            case \Config\AppConstant::Reading:
                return $this->dm->getRepository(\Test\Documents\Question\ReadingQuestionDocument::class);
            break;
            case \Config\AppConstant::Listening:
                return $this->dm->getRepository(\Test\Documents\Question\ListeningQuestionDocument::class);
            break;
            case \Config\AppConstant::Writing:
                return $this->dm->getRepository(\Test\Documents\Question\WritingQuestionDocument::class);
            break;
        }
        return null;
    }

    public function generateQuestion($citerial, $notInsources, & $messages) {
        if ($citerial->getGenerateFrom() !== \Config\AppConstant::Random) {
            return $citerial;
        }
        
        $questionDTO = $citerial->getQuestionInfo();
        $questionRepository = $this->getRepositoryByType($questionDTO->getType());
        if ($questionRepository === null) {
            $messages[] = $this->translator->translate('Cannot find repository with type %type%', ['%type%' => $questionDTO->getType()]);
            return false;
        }

        $question = $questionRepository->generateRandomQuestion($questionDTO->getType(), $questionDTO->getSubType(), $questionDTO->getNumberSubQuestion(), $notInsources);
        if (!$question) {
            $generateQuestionCiterial = [
                '%type%' => $questionDTO->getType(),
                '%subType%' => $questionDTO->getSubType(),
                '%numberSubQuestion%' => $questionDTO->getNumberSubQuestion(),
                '%sources%' => implode(',', $notInsources)
            ];

            $messages[] = $this->translator->translate('Cannot generate question with citerials: [type => %type%, subType => %subType%, numberSubQuestion => %numberSubQuestion%, sources=>%sources%]', $generateQuestionCiterial);
            return false;
        }

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $questionDTO = $documentToDTOConvertor->convertToDTO($question);

        return $questionDTO;
    }
}
