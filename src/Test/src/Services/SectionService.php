<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class SectionService implements SectionServiceInterface, HandlerInterface
{
    private $container;
    private $dm;
    private $options;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
    }

    public function isHandler($param, $options = []){
        return true;
    }
    
    public function createSection(\Test\DTOS\SectionDTO $sectionDTO, & $dto, & $messages) {
        $messages = [];

        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($sectionDTO);

            $this->dm->persist($document);
            $this->dm->flush();
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $dto = $documentToDTOConvertor->convertToDTO($document);

            return true;
        } catch(\Exception $e){
            $messages[] = 'There is error with create section, Please check admin site';
           
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        }        
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
