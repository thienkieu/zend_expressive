<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToTestResultDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
    
    public function isHandleConvertDTOToDocument($dtoObject, $options = []) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Test\TestWithSectionDTO && isset($options['toClass'])) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {  
        $document = new \Test\Documents\ExamResult\TestWithSectionDocument();
        $document->setTitle($dto->getTitle());
                
        $sections = $dto->getSections();

        foreach($sections as $section) {
            $sectionDocument = $this->convertor->convertToDocument($section);
            $document->addSection($sectionDocument);            
        }
        
        return $document;
            
    }
}