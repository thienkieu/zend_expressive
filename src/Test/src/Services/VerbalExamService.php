<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Test\Services\Question\QuestionServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
class VerbalExamService extends ExamService implements HandlerInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $translator = null;

    
    public function isHandler($dto, $options = []){
        if ($dto instanceof \Test\DTOs\Exam\ExamDTO  && $dto->getType() === \Config\AppConstant::Verbal) {
            return true;
        }

        return false;
    }

    protected function generateTestForExam($examDocument, $candidate, &$messages) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $examDTO = $documentToDTOConvertor->convertToDTO($examDocument, [\Config\AppConstant::ShowCorrectAnswer => true]);
        $examTest = $this->generateExamTest($examDTO->getTest(), $messages, true);
        if (!$examTest) {
            return false;
        }
        
        $examDocument->setIsStarted(true);
        
        $examResult = new \Test\DTOs\ExamResult\ExamResultHasSectionTestDTO();
        $examResult->setTest($examTest);
        $examResult->setExamType($examDTO->getType());
        
        $candidateDTO = $documentToDTOConvertor->convertToDTO($candidate);
        $examResult->setCandidate($candidateDTO);
        $examResult->setExamId($examDTO->getId());
        $examResult->setTime($examDTO->getTime());
        $examResult->setTitle($examDTO->getTitle());
        $examResult->setStartDate($examDTO->getStartDate());
        
        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $examResultDocument = $dtoToDocumentConvertor->convertToDocument($examResult, [\Config\AppConstant::ToDocumentClass => \Test\Documents\ExamResult\ExamResultHasSectionTestDocument::class]);
        $examResultDocument->setRemainTime($examDTO->getTime() * 60);
        $this->dm->persist($examResultDocument);
        
        $doExamService  = $this->container->get(DoExamServiceInterface::class);
        $doExamService->inValidPin($examDocument->getId(), $candidate->getId());

        return $examResultDocument;
    }

    public function createOrUpdateExam(\Test\DTOs\Exam\ExamDTO $examDTO, & $dto, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        try {
            $options = [];
            $options[\Config\AppConstant::ToDocumentClass] = \Test\Documents\Exam\ExamDocument::class;
         
            $examResultService  = $this->container->get(DoExamResultServiceInterface::class);
            $existExamId = $examDTO->getId();
            if (!empty($existExamId)) {
                $existExamObj = $this->dm->find(\Test\Documents\Exam\ExamDocument::class, $existExamId);
                if (!$existExamObj) {
                    $messages[] = $translator->translate('The exam doesnot exist, Please check it again.');
                    return false;
                }

                $existedExamResult = $examResultService->isExistExamResultMarkDone($existExamId);
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
            
            if (!empty($existExamId)) {
                $examResultService->removeExamResultByExamId($existExamId);
            }

            $this->assignPin($document, empty($existExamId));
            $this->dm->persist($document);
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document, [\Config\AppConstant::ShowCorrectAnswer => true]);
            
            $candidates = $document->getCandidates();
            foreach($candidates as $candidate) {
                $examResultDocument = $this->generateTestForExam($document, $candidate, $messages);
                $adapter = new \Test\Convertor\Adapter\Documents\ToExamResultSummaryDocumentAdapter(null, null);
                $summaries = $adapter->convert($examResultDocument);
                $candidate->setResultSummary($summaries);
            }
            
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
}
