<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents;

use Infrastructure\Convertor\ConvertDTOAToDocumentAdapterInterface;

class ToCandidateDocumentAdapter implements ConvertDTOAToDocumentAdapterInterface {
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
        if ($dtoObject instanceof \Test\DTOs\Exam\CandidateDTO && isset($options[\Config\AppConstant::ToDocumentClass]) && $options[\Config\AppConstant::ToDocumentClass] === \Test\Documents\Exam\ExamDocument::class) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto, $options = []) 
    {  
        $document = new \Test\Documents\Exam\CandidateDocument();
        $document->setName($dto->getName());
        $document->setEmail($dto->getEmail());
        $document->setObjectId($dto->getObjectId());
        $document->setType($dto->getType());
        $pin = $dto->getPin();
        if (!empty($pin)) {
            $document->setPin($pin);
        }

        return $document;
    }
}