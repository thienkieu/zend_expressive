<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class TypeService implements Interfaces\TypeServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;
    private $types = null;
    private $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
        $this->translator = $this->container->get(\Config\AppConstant::Translator);;
    }

    public function isHandler($param, $options = []){
        return true;
    }

    private function standardizedName($typeName) {
        return preg_replace('/\s\s+/', ' ', trim(strtoupper($typeName), ' '));
    }

    public function getTypeByName($parentName, $subTypeName = '') {
        $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
        return $typeRepository->getTypeByName($parentName, $subTypeName);
    }

    public function getTypeById($id) {
        $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
        return $typeRepository->find($id);
    }
        
    public function getAllTypes(& $ret) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
        $typeDocuments = $typeRepository->findAll();

        $ret = [];
        foreach ($typeDocuments as $type) {
            $dto = $documentToDTOConvertor->convertToDTO($type);
            $ret[] = $dto;
        }
        
        return true;
    }

    public function getTypes($content, & $messages, $pageNumber = 1, $itemPerPage = 25) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
        $typeDocuments = $typeRepository->findBy(['parentType'=> null]);

        $types = [];
        foreach($typeDocuments as $parent) {
            $types[] = $documentToDTOConvertor->convertToDTO($parent);            
        }

        $ret = new \stdClass();
        $ret->type = $types;
        $ret->pageNumber = $pageNumber;
        $ret->itemPerPage = $itemPerPage;

        return $ret;
    }

    
    public function createType($dto, & $returnDTO, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        $isExistedType = $this->getTypeByName($dto->getName());        
        if ($isExistedType) {
            $messages[] = $translator->translate('Type is existed, Please check your spelling again!');
            return false;
        }

        
        $parentType = null;
        $parentName = $dto->getParentName();
        if (!empty($parentName)) {
            $parentType = $this->getTypeByName($parentName);
            if (!$parentType) {
                $messages[] = $translator->translate('Type isnot existed, Please check your spelling again!');
                return false;
            }
        }

        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($dto);
            
            $document->setParentType($parentType);
            
            $this->dm->persist($document);
            $this->dm->flush();
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $returnDTO = $documentToDTOConvertor->convertToDTO($document);

            $messages[] = $translator->translate('Your type have been created successfully!');
            return true;
        } catch(\Exception $e){
            $messages[] = $translator->translate('There is error with create type, Please check admin site');
           
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        }

               
    }
}
