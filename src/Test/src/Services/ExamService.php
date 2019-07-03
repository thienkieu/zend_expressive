<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Test\Services\Question\QuestionServiceInterface;

class ExamService implements ExamServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');        
    }

    public function isHandler($dto, $options = []){
        return true;
    }

    protected function assignPin(&$document) {
        $candidates = $document->getCandidates();
        $pins = \Infrastructure\CommonFunction::generateUniquePin(count($candidates));
        $index =0;
        foreach ($candidates as $candiate) {
            $candiate->setPin($pins[$index]);
            $index++;
        }
    }

    public function createExam(\Test\DTOs\Exam\ExamDTO $examDTO, & $dto, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        try {
            //TODO verify number of source to make sure able to generate test random.
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($examDTO, [\Config\AppConstant::ToDocumentClass => \Test\Documents\Exam\ExamHasSectionTestDocument::class]);
            $this->assignPin($document);
            $this->dm->persist($document);
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document);

            $candidates = $dto->getCandidates();
            foreach ($candidates as $candiate) {
                $examResultDocument = $this->generateExamResult($dto, $candiate);
                $this->dm->persist($examResultDocument);
            }

            $this->dm->flush();
            
            $messages[] = $translator->translate('Your exam have been created successfull!');
            return true;
        } catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage(); 
            $dto = null;      
            return false; 
        } catch(\Exception $e){
            $messages[] = $translator->translate('There is error with create section, Please check admin site');
            $dto = null;
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        }        
    }

    protected function generateExamResult($examDTO, $candiateDTO) {
        $testForDoExam = new \Test\DTOs\Test\TestWithSectionDTO();
        $sectionsForDoExam = [];
        $questionService = $this->container->get(QuestionServiceInterface::class);

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
                $testQuestionDTO->setGenerateFrom(\Config\AppConstant::Pickup);
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
        
        $examResult = new \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO();
        $examResult->setTest($testForDoExam);
        $examResult->setCandidate($candiateDTO);
        $examResult->setExamId($examDTO->getId());
        $examResult->setTime($examDTO->getTime());
        $examResult->setTitle($examDTO->getTitle());
        $examResult->setStartDate($examDTO->getStartDate());

        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $examResultDocument = $dtoToDocumentConvertor->convertToDocument($examResult, [\Config\AppConstant::ToDocumentClass => \Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class]);
        $examResultDocument->setRemainTime($examDTO->getTime() * 60);
        
        return $examResultDocument;
    }
    

    public function enterPin($dto, & $results, & $messages) {
        $testRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamHasSectionTestDocument::class);
        $document = $testDocuments = $testRepository->getCandidateInfo($dto->pin);
        if ($document) {
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $results = $documentToDTOConvertor->convertToDTO($document);
            return true;
        }
        
        $translator = $this->container->get(\Config\AppConstant::Translator);
        $messages[] = $translator->translate('There isnot exist candidate with pin', ['%pin%' => $dto->pin]);
        return false;
    }
    public function getTests(& $ret, & $messages, $pageNumber = 1, $itemPerPage = 25) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $testRepository = $this->dm->getRepository(\Test\Documents\Test\BaseTestDocument::class);
        $testDocuments = $testRepository->findAll();
        
        //TODO need pagination
        $tests = [];
        foreach ($testDocuments as $test) {
            $dto = $documentToDTOConvertor->convertToDTO($test);
            $tests[] = $dto;
        }

        $ret = new \stdClass();
        $ret->data = $tests;
        $totalItems = count($tests);
        $ret->itemPerPage = $itemPerPage;
        $ret->pageNumber = $pageNumber;
        $ret->totalPage = $totalItems % $itemPerPage > 0 ? (int)($totalItems / $itemPerPage) + 1 : $totalItems / $itemPerPage;

        return true;
    }

    public function getSectionByContent($content) {
        $repository = $this->dm->getRepository(Documents\SectionDocument::class);
        $obj = $repository->find("5caac4c7ce10c916c8007032");
               
        $builder = $dm->createQueryBuilder(array(Documents\ReadingSectionDocument::class, Documents\ListeningSectionDocument::class));
        $builder = $builder->field('questions.content')->equals(new \MongoRegex('/.*'.$content.'.*/i'));
        $query = $builder->getQuery();
        $documents = $query->execute();

        return $document;
    }
}
