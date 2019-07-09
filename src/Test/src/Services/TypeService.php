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

    public function isExistTypeName($typeName) {
        if (!$this->types) {
            $typeData = [];
            $ok = $this->getAllTypes($typeData);
            if ($ok) {
                $this->types = [];
                foreach ($typeData as $type) {
                    $this->types[$type->getId()] = $this->standardizedName($type->getName());
                }
            }
        }
        
        if (!$this->types) return false;
        $values = array_values($this->types);
        $needToCheckValue = $this->standardizedName($typeName);

        return in_array($needToCheckValue, $values);
    }
    
    public function isExistSubTypeName($typeName, $subTypeName) {
        $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
        $type = $typeRepository->getType($typeName);
        if (!$type) {
            return false;
        }
        
        $subTypes = $type->getSubTypes();
        foreach ($subTypes as $subType) {
           if ($subType->getName() === $subTypeName) {
               return true;
           }
        }

        return false;
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

    public function getTypes(& $ret, & $messages, $pageNumber = 1, $itemPerPage = 25) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
        $typeDocuments = $typeRepository->findAll();

        $types = [];
        foreach ($typeDocuments as $type) {
            $dto = $documentToDTOConvertor->convertToDTO($type);
            $types[] = $dto;
        }

        $ret = new \stdClass();
        $ret->data = $types;
        $ret->pageNumber = $pageNumber;
        $ret->itemPerPage = $itemPerPage;

        return true;
    }

    public function createType($dto, & $returnDTO, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        $isExistedType = $this->isExistTypeName($dto->getName(), $messages);
        if ($isExistedType) {
            $messages[] = $translator->translate('Type is existed, Please check your spelling again!');
            return false;
        }

        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($dto);
            
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
