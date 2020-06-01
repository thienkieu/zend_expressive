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

        $platformService = $this->container->get(\Test\Services\Interfaces\PlatformServiceInterface::class);
        $platformDocument = $platformService->getPlatformById($dto->getPlatform());
        if (!$platformDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Platform not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setPlatform($platformDocument);
        
        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $user = $authorizationService->getUser();
        $document->setUser($user);

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