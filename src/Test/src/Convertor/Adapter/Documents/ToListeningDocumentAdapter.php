<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\SourceServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ToListeningDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\ListeningQuestionDTO && !isset($options[\Config\AppConstant::ToDocumentClass])) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Question\ListeningQuestionDocument();
        if (isset($options[\Config\AppConstant::ExistingDocument]))  {
            $document = $options[\Config\AppConstant::ExistingDocument];            
        }

        $document->setContent($dto->getContent());
        
        
        $path = $this->replaceHost($dto->getPath());
        $document->setPath($path);

        $document->setRepeat($dto->getRepeat());

        $sourceService = $this->container->get(SourceServiceInterface::class);
        $sourceDocument = $sourceService->getSourceByName($dto->getSource());
        if (!$sourceDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Source not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setSource($sourceDocument);
        
        $typeService = $this->container->get(TypeServiceInterface::class);
        $typeDocument = $typeService->getTypeById($dto->getTypeId());
        if (!$typeDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Type not found, please check it again.');
            throw new \Exception($message);
        }
        $document->setType($typeDocument);
        $document->setTypeId($typeDocument->getId());
        $document->setSubTypeId($typeDocument->getParent()->getId());
        
        $questions = $dto->getSubQuestions();

        $questionDocuments = new ArrayCollection();
        foreach($questions as $question) {
            $questionDocuments->add($this->convertor->convertToDocument($question, $options));
        }
        $document->setSubQuestions($questionDocuments);     
        return $document;
            
    }

    protected function replaceHost($content, $options = []) {
        $host = \Infrastructure\CommonFunction::getServerHost();

        $content = str_replace($host, '', $content);
        return $content;
    }
}