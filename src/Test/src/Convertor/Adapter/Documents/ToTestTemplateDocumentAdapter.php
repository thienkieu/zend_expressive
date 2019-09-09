<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Doctrine\Common\Collections\ArrayCollection;
class ToTestTemplateDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        
        if ($dtoObject instanceof \Test\DTOs\Test\TestTemplateDTO && 
            isset($options[\Config\AppConstant::ToDocumentClass]) 
        ) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $options[\Config\AppConstant::ToDocumentClass] = \Test\Documents\Test\TestWithSectionDocument::class;
        $document = new \Test\Documents\Test\TestTemplateDocument();
        if (isset($options[\Config\AppConstant::ExistingDocument])) {
            $document = $options[\Config\AppConstant::ExistingDocument];            
        }
        
        $path = $this->replaceHost($dto->getPath());
        $document->setPath($path);

        $document->setTitle($dto->getTitle());
        $document->getSections()->clear();

        $sections = $dto->getSections();
       
        foreach($sections as $section) {
            $sectionDocument = $this->convertor->convertToDocument($section, $options);
            $document->addSection($sectionDocument);            
        }
        
        return $document;   
    }

    protected function replaceHost($content, $options = []) {
        $host = \Infrastructure\CommonFunction::getServerHost();

        $content = str_replace($host, '', $content);
        return $content;
    }
}