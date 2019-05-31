<?php 

declare(strict_types=1);

namespace Test\Convertor\Adapter\Documents\Test;


use Infrastructure\Convertor\ConvertAdapterInterface;
use Test\Convertor\Adapter\Documents\ToReadingDocumentAdapter;
use Test\Convertor\Adapter\Documents\ToWritingDocumentAdapter;
use Test\Convertor\Adapter\Documents\ToListeningDocumentAdapter;


class ToQuestionDocumentAdapter implements ConvertAdapterInterface {
    public function isHandle($dtoObject) : bool
    {
        if ($dtoObject instanceof \Test\DTOs\Test\QuestionDTO) {
            return true;
        }
        
        return false;
    }
    
    public function convert($dto) 
    {
        $document = new \Test\Documents\Test\QuestionDocument();
        $document->setGenerateFrom($dto->getGenerateFrom());

        switch($dto->getGenerateFrom()) {
            case 'pickup' :
                switch($dto->getQuestionInfo()->getType()) {
                    case 'reading':
                        $toReadingDocumentAdatper = new ToReadingDocumentAdapter();
                        $document->setQuestionInfo($toReadingDocumentAdatper->convert($dto->getQuestionInfo()));
                    break;
                    case 'writing':
                        $toWritingDocumentAdatper = new ToWritingDocumentAdapter();
                        $document->setQuestionInfo($toWritingDocumentAdatper->convert($dto->getQuestionInfo()));
                    break;
                    case 'listening':
                        $toListeningDocumentAdatper = new ToListeningDocumentAdapter();
                        $document->setQuestionInfo($toListeningDocumentAdatper->convert($dto->getQuestionInfo()));
                    break;
                    default:
                    break;
                }
            break;
            case 'random' :
                $toRandomDocumentAdatper = new ToRandomQuestionDocumentAdapter();
                $document->setQuestionInfo($toRandomDocumentAdatper->convert($dto->getQuestionInfo()));
            break;
            default:
            
            break;
        }
       
        return $document;            
    }
}