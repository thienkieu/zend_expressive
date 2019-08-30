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
    protected $container;
    protected $dm;
    protected $options;
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
            $oldPin = $candiate->getPin();
            if (empty($oldPin)) {
                $candiate->setPin($pins[$index]);
            }
            
            $index++;
        }
    }

    public function updateExamResultSummary($examId, $candidateId, $resultSummary) {
        $existExamObj = $this->dm->find(\Test\Documents\Exam\ExamDocument::class, $examId);
        $candidateDocuments  = $existExamObj->getCandidates();
        foreach($candidateDocuments as $candidate) {
            if ($candidate->getId() === $candidateId) {
                $candidate->setResultSummary($resultSummary);
                break;
            }
        }
    }

    public function updateExamStatus($examId) {
        $existExamObj = $this->dm->find(\Test\Documents\Exam\ExamDocument::class, $examId);
        $candidateDocuments  = $existExamObj->getCandidates();
        $isDoneManualInputMark = true;
        foreach($candidateDocuments as $candidate) {
            $resultSummary = $candidate->getResultSummary();
            if ($resultSummary->isEmpty()) {
                $isDoneManualInputMark = false;
            } else {
                foreach($resultSummary as $resultItem) {
                    if (!$resultItem->getIsScored()) {
                        $isDoneManualInputMark = false;
                        break;
                    }
                }
            }

        }
        $existExamObj->setIsScored($isDoneManualInputMark);
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
            $options = [];
            $options[\Config\AppConstant::ToDocumentClass] = \Test\Documents\Exam\ExamDocument::class;
         
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
               
                $options[\Config\AppConstant::ExistingDocument] = $existExamObj;
            }

            if ($this->existExamWithTitle($examDTO->getTitle(), $existExamTitle)) {
                if ($examDTO->getId() != $existExamTitle->getId()) {
                    $messages[] = $translator->translate('There is existing exam with the same title, Please enter another title.');
                    return false;
                }
            }
            
            $examTest = $this->generateExamTest($examDTO->getTest(), $messages);
            
            if (!$examTest) {
                return false;
            }

            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($examDTO, $options);
            
            $this->assignPin($document);
            $this->dm->persist($document);
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document, [\Config\AppConstant::ShowCorrectAnswer => true]);

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
            if ($dto === false) {
                return false;
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

    public function generateExamTest($test, & $messages, $keepCorrectAnswer = false, $options = []) {
        try {
            $testForDoExam = new \Test\DTOs\Test\TestWithSectionDTO();
            $sectionsForDoExam = [];
            $questionService = $this->container->get(QuestionServiceInterface::class);
        
            $sections = $test->getSections();
            $sources = [];
            $questionIds = [];
            if (isset($options['questionId'])) {
                $questionIds = $options['questionId'];
            }

            foreach ($sections as $section) {
                $questionsForSection = [];
                $questions = $section->getQuestions();                
                foreach ($questions as $question) {
                    $questionInfo = $question->getQuestionInfo();
                    
                    if (!isset($sources[$questionInfo->getTypeId()])) $sources[$questionInfo->getTypeId()] = [];
                    
                    $q = $questionService->generateQuestion($question, $sources[$questionInfo->getTypeId()], $questionIds, $keepCorrectAnswer);
                    $sources[$q->getTypeId()][] = $q->getSourceId();
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
            $testForDoExam->setReferId($test->getReferId());
            
            return $testForDoExam;
        } catch(\Test\Exceptions\GenerateQuestionException $e) {
            $messages[] =  $e->getMessage();            
            return false; 
        }
    }
    
    public function enterPin($dto, & $results, & $messages) {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $document = $examRepository->getCandidateInfo($dto->pin);
        if ($document) {
            $doExamService = $this->container->get(DoExamServiceInterface::class);
            $ok = $doExamService->isAllowAccessExam($document);
            if (!$ok) {
                $messages[] = $this->translator->translate('Your PIN is invalid.');
                return false;
            }

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
            $dto = $documentToDTOConvertor->convertToDTO($exam, [\Config\AppConstant::ShowCorrectAnswer => true]);
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

    public function getExam($id, & $ret, &$messages) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $examDocument = $examRepository->find($id);
        
        if (!$examDocument) {
            $messages[] = $this->translator->translate('The exam doesnot exist, Please check it again.');
            return false;
        }   
        
        $ret = $documentToDTOConvertor->convertToDTO($examDocument, [\Config\AppConstant::ShowCorrectAnswer => true]);
        return true;
    }

    public function existExamWithTitle($title, &$document) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $document = $examRepository->findOneBy(['title' => $title]);
        
        if ($document) {
            return true;
        }
        
        return false;
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

    public function deleteExam($id, &$messages) {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $examDocument = $examRepository->find($id);
        if (!$examDocument) {
            $messages[] = $this->translator->translate('The exam doesnot exist, Please check it again.');
            return false;
        }

        $examResultService  = $this->container->get(DoExamResultServiceInterface::class);
        $existedExamResult = $examResultService->isExistResultOfExam($id);
        if ($existedExamResult) {
            $messages[] = $this->translator->translate('Cannot delete this exam because this exam have been used.');
            return false;
        }

        $this->dm->remove($examDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('The exam have been deleted successfully!');
        return true;
    }

    public function getExamNotStartedByTestId($testId) {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        return $examRepository->getExamNotStartedByTestId($testId);
    }

    public function getExamNotStarted() {
        $examRepository = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $examDocuments = $examRepository->getExamNotStarted();
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $examDTOs = [];
        foreach($examDocuments as $examDocument) {
            $examDTOs[] = $documentToDTOConvertor->convertToDTO($examDocument);
        }

        return $examDTOs;
    }

    public function getTypes() {
        $verbal = new \stdClass();
        $verbal->value = 'Verbal';
        $verbal->name = $this->translator->translate('Verbal Exam Type');

        $skill = new \stdClass();
        $skill->value = 'Skill';
        $skill->name = $this->translator->translate('Skill Exam Type');
        return [
            $skill,
            $verbal,
        ];
    }
}
