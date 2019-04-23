<?php 

declare(strict_types=1);

namespace Test\Factories\Convertor;

use Zend\Hydrator\ReflectionHydrator;

class DTOToSectionDocumentFactory {
    public static function convertToSectionDocument(\Test\DTOs\SectionDTO $dto) {
        switch($dto->getType()) {
            case \Test\Enum\SectionType::Reading :
                $document = new \Test\Documents\ReadingSectionDocument();
                $document->setContent($dto->getContent());

                DTOToSectionDocumentFactory::addQuestionToDocument($dto->getQuestion(), $document);
                return $document;
            break;

            case \Test\Enum\SectionType::Listening :
                $document = new \Test\Documents\ListeningSectionDocument();
                $document->setContent($dto->getContent());
                $document->setPath($dto->getPath());
                $document->setRepeat($dto->getRepeat());

                DTOToSectionDocumentFactory::addQuestionToDocument($dto->getQuestion(), $document);
                return $document;
            break;

            case \Test\Enum\SectionType::Writing :
                $document = new \Test\Documents\WritingSectionDocument();
                $document->setContent($dto->getContent());

                DTOToSectionDocumentFactory::addQuestionToDocument($dto->getQuestion(), $document);
                return $document;
            break;       
        }

        return null;
    }

    public static function addQuestionToDocument($questions, $document) {
        foreach($questions as $question) {
            $q = new \Test\Documents\QuestionDocument();                    
            $q->setContent($question['content']);
            
            $answers = $question['answers'];
            foreach($answers as $answer){
                $a = new \Test\Documents\AnswerDocument();
                $a->setContent($answer['content']);
                $q->addAnswer($a);
            }
            
            $document->addQuestion($q);
        }
    }

}