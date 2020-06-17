<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;
use Test\Services\Interfaces\PlatformServiceInterface;

class ToTypeDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Question\TypeDTO) {
            return true;
        }

        return false;
    }
    
    public function convert($dtoObject, $options = []) 
    {
        $document = new \Test\Documents\Question\TypeDocument();
        $document->setName($dtoObject->getName());

        $platformService = $this->container->get(PlatformServiceInterface::class);
        $platformDocument = $platformService->getPlatformById($dtoObject->getPlatform());
        if (!$platformDocument) {
            $translator = $this->container->get(\Config\AppConstant::Translator);
            $message = $translator->translate('Platform not found, please check it again.');
            throw new \Infrastructure\Exceptions\DataException($message);
        }
        $document->setPlatform($platformDocument);
        $document->setIsManualScored($dtoObject->getIsManualScored());
        
        return $document;            
    }
}