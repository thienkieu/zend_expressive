<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;

class SectionService implements SectionServiceInterface
{
    private $container;
    private $dm;
    private $options;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
    }

    public function createSection(\Test\DTOS\SectionDTO $sectionDTO) {
        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $document = $dtoToDocumentConvertor->convertToDocument($sectionDTO);

        $this->dm->persist($document);
        $this->dm->flush();
        
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
