<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class TestTemplateService implements Interfaces\TestTemplateServiceInterface, HandlerInterface
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
    
    public function updateTest(\Test\DTOs\Test\BaseTestDTO $testDTO, & $messages, & $outDTO) {
        $testId = $testDTO->getId();
        if (empty($testId)) {
            $messages[] = $this->translator->translate('Your test doesnot exist!');
            return false;
        }

        try {
            $testRepository = $this->dm->getRepository(\Test\Documents\Test\TestWithSectionDocument::class);
            $testDocument = $testRepository->find($testDTO->getId());
            if (!$testDocument) {
                $messages[] = $this->translator->translate('Your test doesnot exist!');
                return false;
            }

            $examService = $this->container->get(ExamServiceInterface::class);
            $examDTO = $examService->generateExamTest($testDTO, $messages);
            if (!$examDTO) {
                return false;
            }

            $this->dm->remove($testDocument);
            $this->dm->flush();
            
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $newTestDocument = $dtoToDocumentConvertor->convertToDocument($testDTO);
            $this->dm->persist($newTestDocument);

            $newTestEmbedDocument = $dtoToDocumentConvertor->convertToDocument($testDTO, [\Config\AppConstant::ToDocumentClass => \Test\Documents\ExamResult\TestWithSectionDocument::class]);
            $examNotStarteds = $examService->getExamNotStartedByTestId($testDTO->getId());
            foreach($examNotStarteds as $examDocument) {
                $examDocument->setTest($newTestEmbedDocument);
            }

            $this->dm->flush();
            $messages[] = $this->translator->translate('The test have been updated successfully!');
            return true;
            
        } catch(\Exception $e){
            $messages[] = $this->translator->translate('There is error with update test, Please check admin site');
           
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        } 
    }

    public function createTest(\Test\DTOs\Test\BaseTestDTO $testDTO, & $messages, & $outDTO) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($testDTO);
            
            $examService = $this->container->get(ExamServiceInterface::class);
            $examDTO = $examService->generateExamTest($testDTO, $messages);
            if (!$examDTO) {
                return false;
            }

            $this->dm->persist($document);
            $this->dm->flush();
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document);
            $outDTO = $dto;

            $messages[] = $translator->translate('Your test have been created successfull!');
            return true;
        } catch(\Exception $e){
            $messages[] = $translator->translate('There is error with create section, Please check admin site');
           
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        }        
    }

    public function getTemplates(& $ret, & $messages, $filterData, $pageNumber = \Config\AppConstant::PageNumber, $itemPerPage = \Config\AppConstant::ItemPerPage) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $testTemplateRepository = $this->dm->getRepository(\Test\Documents\Test\TestTemplateDocument::class);
        $testDocuments = $testTemplateRepository->getTemplateWithPagination($filterData, $itemPerPage, $pageNumber);
        $documents = $testDocuments['templates'];
        
        $testTemplates = [];
        foreach ($documents as $test) {
            $dto = $documentToDTOConvertor->convertToDTO($test);
            $testTemplates[] = $dto;
        }

        $messages = [];
        $ret = new \stdClass();
        $ret->templates = $testTemplates;
        $totalItems = $testDocuments['totalDocument'];
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

    public function deleteTest($testId, & $messages) {
        $testRepository = $this->dm->getRepository(\Test\Documents\Test\TestWithSectionDocument::class);  
        $testDocument = $testRepository->find($testId);
        if (!$testDocument) {
            $messages[] = $this->translator->translate('The test is not found, Please check it again.');
            return false;
        }

        $this->dm->remove($testDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('Your test have been deleted successfully!');
        return true;
    }
}