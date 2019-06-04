<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertAdapterInterface;

class ToTestDocumentAdapter implements ConvertAdapterInterface {
    protected $container;
    protected $convertor;

    /**
     * Class constructor.
     */
    public function __construct($container, $convertor)
    {
        $this->container = $container;
        $this->convertor = $convertor;
    }
    
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Test\TestWithSectionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\Test\TestWithSectionDocument();
        $document->setTitle($dto->getTitle());
                
        $sections = $dto->getSections();

        foreach($sections as $section) {
            $sectionDocument = $this->convertor->convertToDocument($section);
            $document->addSection($sectionDocument);            
        }
        
        return $document;
            
    }
}