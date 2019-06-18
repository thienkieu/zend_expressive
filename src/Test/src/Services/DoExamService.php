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

    public function isHandler($dto, $options = []){
        return true;
    }
    
    public function updateAnswer($dto, & $messages) {

        $examResultRepository = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class);
        $results = $testDocuments = $examResultRepository->updateAnwser($dto->examId, $dto->candidateId, $dto->questionId, $dto->subQuestionId, $dto->answerId);
        if ($results['ok'] != 1) {
            $messages[] = $this->translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
            return false;
        }
        
        return $results;
        
    }

    public function doExam($dto, & $results, & $messages) {
        try {
            $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamHasSectionTestDocument::class);
            $document = $testDocuments = $examRepository->getExamInfo($dto->pin);
            if (!$document) {
                $messages[] = $this->translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
                return false;
            }

            $candidates = $document->getCandidates();
            if (!$candidates[0]->getIsPinValid()) {
                $messages[] = $this->translator->translate('Your pin \'%pin%\' is used, Please notify to admin to get new pin', ['%pin%' => $dto->pin]);
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
                    
                    $q = $questionService->generateQuestion($question, $sources);                    
                    $sources[] = $q->getSource();

                    $testQuestionDTO = new \Test\DTOs\Test\QuestionDTO();
                    $testQuestionDTO->setId($q->getId());
                    $testQuestionDTO->setGenerateFrom($question->getGenerateFrom());
                    $testQuestionDTO->setQuestionInfo($q);
                   
                    $questionsForSection[] = $testQuestionDTO;
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

            $examDTO->setTest($testForDoExam);

            $results = $examDTO;
            $candidates = $examDTO->getCandidates();

            //$pinService = $this->container->get(PinServiceInterface::class);
            //$pinService->inValidPin($examDTO->getId(), $dto->pin);

            $examResult = new \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO();
            $examResult->setTest($testForDoExam);
            $examResult->setCandidate($candidates[0]);
            $examResult->setExamId($examDTO->getId());

            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $examResultDocument = $dtoToDocumentConvertor->convertToDocument($examResult);
        
            $this->dm->persist($examResultDocument);
            $this->dm->flush();

            return true;
        }catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();       
            return false;   
        }

    }
}
