<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToTestEmbedDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Test\TestWithSectionDTO && isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\ExamResult\TestWithSectionDocument();
        $document->setTitle($dto->getTitle());
        $document->setReferId('');

        $referId = $dto->getReferId();
        if (!empty($referId)) {
            $document->setReferId($referId);
        } else {
            $testId = $dto->getId();
            if (!empty($testId)) $document->setReferId($testId);
        }
        
        $sections = $dto->getSections();

        foreach($sections as $section) {
            $sectionDocument = $this->convertor->convertToDocument($section, $options);
            $document->addSection($sectionDocument);            
        }
        
        return $document;
            
    }
}