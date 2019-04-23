<?php

declare(strict_types=1);

namespace Test\Services;

class SectionService implements SectionServiceInterface
{
    private $container;
    private $options;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
    }

    public function createSection(\Test\DTOS\SectionDTO $sectionDTO) {
        $document = \Test\Factories\Convertor\DTOToSectionDocumentFactory::convertToSectionDocument($sectionDTO);

        var_dump($document);die;
        $dm = $this->container->get('documentManager');
        $dm->persist($document);
        $dm->flush();
        
        return true;
    }
    
}
