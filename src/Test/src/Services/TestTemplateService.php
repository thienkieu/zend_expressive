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

    public function createTestTemplate(\Test\DTOs\Test\BaseTestDTO $testDTO, & $messages, & $outDTO) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($testDTO, [\Config\AppConstant::ToDocumentClass => \Test\Documents\Test\TestTemplateDocument::class]);

            $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
            $userDocument = $authorizationService->getUser();
            $document->setUser($userDocument);
            //echo $userDocument->getObjectId();
            
            $this->dm->persist($document);
            $this->dm->flush();
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document);
            $outDTO = $dto;

            $messages[] = $translator->translate('Your test template have been created successfull!');
            return true;
        } catch(\Exception $e){
            $messages[] = $translator->translate('There is error with create test template, Please check admin site');
           
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

    public function deleteTestTemplate($testId, & $messages) {
        $testTemplateRepository = $this->dm->getRepository(\Test\Documents\Test\TestTemplateDocument::class);  
        $testDocument = $testRepository->find($testId);
        if (!$testDocument) {
            $messages[] = $this->translator->translate('The test template is not found, Please check it again.');
            return false;
        }

        $this->dm->remove($testDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('Your test template have been deleted successfully!');
        return true;
    }
}
