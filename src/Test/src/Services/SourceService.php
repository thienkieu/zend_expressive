<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;
use Test\Services\Question\QuestionServiceInterface;


class SourceService implements Interfaces\SourceServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $sources = null;
    private $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
        $this->translator = $this->container->get(\Config\AppConstant::Translator);

    }

    public function isHandler($param, $options = []){
        return true;
    }

    private function standardizedName($sourceName) {
        return preg_replace('/\s\s+/', ' ', trim(strtoupper($sourceName), ' '));
    }

    public function isExistSourceName($sourceName, &$messages) {
        if (!$this->sources) {
            $sourceData = [];
            $ok = $this->getAllSource($sourceData, $messages);
            if ($ok) {
                $this->sources = [];
                foreach ($sourceData as $source) {
                    $this->sources[$source->getId()] = $this->standardizedName($source->getName());
                }
            }
        }
        
        if (!$this->sources) return false;
        $values = array_values($this->sources);
        $needToCheckValue = $this->standardizedName($sourceName);

        return in_array($needToCheckValue, $values);
    }
    
    public function getSourceByName($name) {
        $sourceRepository = $this->dm->getRepository(\Test\Documents\Question\SourceDocument::class);  
        return $sourceRepository->findOneBy(['name' => $name]);
    }

    public function getSourceById($id) {
        $sourceRepository = $this->dm->getRepository(\Test\Documents\Question\SourceDocument::class);  
        return $sourceRepository->find($id);
    }

    public function getAllSource(& $ret, & $messages) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $sourceRepository = $this->dm->getRepository(\Test\Documents\Question\SourceDocument::class);  
        $sourceDocuments = $sourceRepository->findAll();

        $ret = [];
        foreach ($sourceDocuments as $source) {
            $dto = $documentToDTOConvertor->convertToDTO($source);
            $ret[] = $dto;
        }
        
        return true;
    }

    public function getSources(& $ret, & $messages, $pageNumber = 1, $itemPerPage = 25) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $sourceRepository = $this->dm->getRepository(\Test\Documents\Question\SourceDocument::class);  
        $sourceDocuments = $sourceRepository->findAll();

        $sources = [];
        foreach ($sourceDocuments as $source) {
            $dto = $documentToDTOConvertor->convertToDTO($source);
            $sources[] = $dto;
        }

        $ret = new \stdClass();
        $ret->data = $sources;
        $ret->pageNumber = $pageNumber;
        $ret->itemPerPage = $itemPerPage;

        return true;
    }

    public function editSource($dto, & $returnDTO, & $messages) {
        $sourceRepository = $this->dm->getRepository(\Test\Documents\Question\SourceDocument::class);  
        $sourceDocument = $sourceRepository->find($dto->getId());
        if (!$sourceDocument) {
            $messages[] = $this->translator->translate('Source not found, please check it again.');
            return false;
        }

        $sourceDocument->setName($dto->getName());
        $this->dm->flush();

        $messages[] = $this->translator->translate('Source is edited successfully!');
        return true;
    }

    public function deleteSource($dto, & $returnDTO, & $messages) {
        $sourceRepository = $this->dm->getRepository(\Test\Documents\Question\SourceDocument::class);  
        $sourceDocument = $sourceRepository->find($dto->getId());
        if (!$sourceDocument) {
            $messages[] = $this->translator->translate('Source not found, please check it again.');
            return false;
        }

        $questionService = $this->container->get(QuestionServiceInterface::class);
        $questionDocuments = $questionService->getQuestionWithSource($sourceDocument->getId());
        if ($questionDocuments) {
            $messages[] = $this->translator->translate('Source is existed in question, Please remove question contain source first.');
            return false;
        }

        $this->dm->remove($sourceDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('Source is deleted successfully!');
        return true;
    }


    public function createSource($dto, & $returnDTO, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        $isExistedSource = $this->isExistSourceName($dto->getName(), $messages);
        if ($isExistedSource) {
            $messages[] = $translator->translate('Source is existed, Please check your spelling again!');
            return false;
        }

        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($dto);
            
            $this->dm->persist($document);
            $this->dm->flush();
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $returnDTO = $documentToDTOConvertor->convertToDTO($document);

            $messages[] = $translator->translate('Your source have been created successfully!');
            return true;
        } catch(\Exception $e){
            $messages[] = $translator->translate('There is error with create source, Please check admin site');
           
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        }
    }
    
}
