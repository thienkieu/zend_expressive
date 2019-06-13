<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class DoExamService implements DoExamServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');    
        $this->translator = $this->container->get(\Config\AppConstant::Translator);    
    }

    public function isHandler($dto){
        return true;
    }

    public function doExam($dto, & $results, & $messages) {
        $testRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamHasSectionTestDocument::class);
        $document = $testDocuments = $testRepository->getExamInfo($dto->pin);
        if (!$document) {
            $messages[] = $translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
            return false;
        }
        
        $testForDoExam = new \Test\DTOs\Test\TestWithSectionDTO();
        $sectionsForDoExam = [];
        $questionService = $this->container->get(QuestionServiceInterface::class);

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $examDTO = $documentToDTOConvertor->convertToDTO($document);
        $test  = $examDTO->getTest();
        $sections = $test->getSections();
        foreach ($sections as $section) {
            $questionsForSection = [];
            $questions = $section->getQuestions();
            $sources = [];
            foreach ($questions as $question) {
                $q = $questionService->generateQuestion($question, $sources, $messages);
                if ($q === false) {
                    // TODO need to define here
                    throw new \Test\Exceptions\GenerateQuestionException($messages[0]);
                }

                $sources[] = $q->getSource();
                $questionsForSection[] = $q;
            }

            $sectionForDoExam = new \Test\DTOs\Test\SectionDTO();
            $sectionForDoExam->setName($section->getName());
            $sectionForDoExam->setDescription($section->getDescription());
            $sectionForDoExam->setQuestions($questionsForSection);    
            
            $sectionsForDoExam[] = $sectionForDoExam;
        }

        $testForDoExam->setSections($sectionsForDoExam);
        $testForDoExam->setId($test->getId());
        $testForDoExam->setTitle($test->getTitle());

        $results = $testForDoExam;
        
        return true;

    }
}
