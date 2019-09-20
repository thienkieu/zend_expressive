<?php

declare(strict_types=1);

namespace Test\Services\Question;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class ListeningQuestionService extends QuestionService
{
    public function isHandler($dto, $options = []){
        if ($dto instanceof \Test\DTOs\Question\ListeningQuestionDTO) {
            return true;
        }

        return false;
    }

    protected function moveMediaToQuestionFolder($file) {
        
    }

    protected function getDuration($path) {
        $mp3file = new \Infrastructure\Utilities\MP3File($path);
        return $mp3file->getDurationEstimate();
    }

    public function createQuestion($dto, &$messages, &$outDTO) {
        $path = $dto->getPath();
        $dto->setDuration($this->getDuration($path));
        
        $isUrl = \Infrastructure\CommonFunction::isURI($path);
        if (\Infrastructure\CommonFunction::isURI($path) === true) {
            $path = \Infrastructure\CommonFunction::replaceHost($path);
            $dto->setPath($path);
        } else {
            $fileName = basename($dto->getPath());
            
            $mediaQuestionFolder = \Config\AppConstant::MediaQuestionFolder.\Config\AppConstant::DS.date('YmdHis');
            \Infrastructure\CommonFunction::createFolder($mediaQuestionFolder);
            $realPath = realpath($mediaQuestionFolder);
            $destinationPath = $realPath.\Config\AppConstant::DS.$fileName;

            \Infrastructure\CommonFunction::moveFile($dto->getPath(), $destinationPath);
            $serverConstant = \Config\AppConstant::HOST_REPLACE;
            $dto->setPath($serverConstant.'/'.\Config\AppConstant::DS.$mediaQuestionFolder.\Config\AppConstant::DS.$fileName);
        }
        
        $existingDocument = null;
        $id = $dto->getId();
        if (!empty($id)) {
            $questionRepository = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
            $existingDocument = $questionRepository->find($dto->getId());
        }

        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $questionDocument = $dtoToDocumentConvertor->convertToDocument($dto, $existingDocument ? [\Config\AppConstant::ExistingDocument => $existingDocument]: []);
        
        $this->dm->persist($questionDocument);
        $this->dm->flush();

        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
        $outDTO = $documentToDTOConvertor->convertToDTO($questionDocument, [\Config\AppConstant::ShowCorrectAnswer => true]);

        $messages[] = $this->translator->translate('The question have been created successfully!');
        return true;
    }

}
