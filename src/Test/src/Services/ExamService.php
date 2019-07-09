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
    protected $translator = null;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');   
        $this->translator = $this->container->get(\Config\AppConstant::Translator);     
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

    public function updateTestOfExam(\Test\DTOs\Exam\EditTestOfExamDTO $editTestOfExamDTO,  & $outDTO, & $messages) {
        $existExamId = $editTestOfExamDTO->getId();
        if (empty($existExamId)) {
            $messages[] = $this->translator->translate('Exam id cannot empty, Please check it again');
            return false;
        }
            
        $existExamObj = $this->dm->find(\Test\Documents\Exam\ExamDocument::class, $existExamId);
        if (!$existExamObj) {
            $messages[] = $this->translator->translate('The exam doesnot exist, Please check it again.');
            return false;
        }
        
        $test = $editTestOfExamDTO->getTest();
        $examTest = $this->generateExamTest($test, $messages);
        if (!$examTest) {
            return false;
        }

        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $document = $dtoToDocumentConvertor->convertToDocument($examTest, [\Config\AppConstant::ToDocumentClass => \Test\Documents\ExamResult\TestWithSectionDocument::class]);
        
        $existExamObj->setTest($document);
        $this->dm->flush();
            
        $messages[] = $this->translator->translate('Your test of exam have been update successfull!');
        return true;
    }

    public function createOrUpdateExam(\Test\DTOs\Exam\ExamDTO $examDTO, & $dto, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        try {
            $existExamId = $examDTO->getId();
            if (!empty($existExamId)) {
                $existExamObj = $this->dm->find(\Test\Documents\Exam\ExamDocument::class, $existExamId);
                if (!$existExamObj) {
                    $messages[] = $translator->translate('The exam doesnot exist, Please check it again.');
                    return false;
                }

                $examResultService  = $this->container->get(DoExamResultServiceInterface::class);
                $existedExamResult = $examResultService->isExistResultOfExam($existExamId);
                if ($existedExamResult) {
                    $messages[] = $translator->translate('Cannot edit this exam because this exam have been used.');
                    return false;
                }

                $this->dm->remove($existExamObj);
            }
            
            $examTest = $this->generateExamTest($examDTO->getTest(), $messages);
            if (!$examTest) {
                return false;
            }

            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($examDTO, [\Config\AppConstant::ToDocumentClass => \Test\Documents\Exam\ExamDocument::class]);
            $this->assignPin($document);
            $this->dm->persist($document);
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document);

            $this->dm->flush();
            
            if (empty($existExamId)) {
                $messages[] = $translator->translate('Your exam have been created successfull!');
            } else {
                $messages[] = $translator->translate('Your exam have been updated successfull!');
            }
            
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

    public function createExamSample(\Test\DTOs\Test\TestWithSectionDTO $testDTO, & $dto, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        try {
            $dto = $this->generateExamTest($testDTO, $messages);
            if (!$dto) {
                return false;
            }
            
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

    public function generateExamTest($test, & $messages) {
        try {
            $testForDoExam = new \Test\DTOs\Test\TestWithSectionDTO();
            $sectionsForDoExam = [];
            $questionService = $this->container->get(QuestionServiceInterface::class);
        
            $sections = $test->getSections();
            $sources = [];
            $questionIds = [];
            foreach ($sections as $section) {
                $questionsForSection = [];
                $questions = $section->getQuestions();                
                foreach ($questions as $question) {
                    $questionInfo = $question->getQuestionInfo();
                    if (!isset($sources[$questionInfo->getType()])) $sources[$questionInfo->getType()] = [];
                    
                    $q = $questionService->generateQuestion($question, $sources[$questionInfo->getType()], $questionIds);
                    $sources[$q->getType()][] = $q->getSource();
                    $questionIds[] = $q->getId();
                
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

            return $testForDoExam;
        } catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();            
            return false; 
        }
    }
    
    public function enterPin($dto, & $results, & $messages) {
        $testRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
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

    public function getExams($filterCriterial, & $ret, & $messages, $pageNumber = 1, $itemPerPage = 25) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $results = $examRepository->getExamWithPagination($filterCriterial, $itemPerPage, $pageNumber);
        
        $exams = [];
        $examDocuments = $results['exams'];
        
        foreach ($examDocuments as $exam) {
            $dto = $documentToDTOConvertor->convertToDTO($exam);
            $exams[] = $dto;
        }

        $ret = new \stdClass();
        $ret->exams = $exams;
        $totalItems = $results['totalDocument'];
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
