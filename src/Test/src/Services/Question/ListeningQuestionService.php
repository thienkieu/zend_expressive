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

    public function createQuestion($dto, &$messages) {
        $path = $dto->getPath();
        $isUrl = \Infrastructure\CommonFunction::isURI($path);
        if (\Infrastructure\CommonFunction::isURI($path) === true) {
            $path = \Infrastructure\CommonFunction::replaceHost($path);
            $dto->setPath($path);
        } else {
            $fileName = basename($dto->getPath());
            $mediaQuestionFolder = \Config\AppConstant::MediaQuestionFolder;
            $realPath = realpath($mediaQuestionFolder);
            $destinationPath = $realPath.\Config\AppConstant::DS.$fileName;

            \Infrastructure\CommonFunction::moveFile($dto->getPath(), $destinationPath);
            $dto->setPath('/'.\Config\AppConstant::DS.$mediaQuestionFolder.\Config\AppConstant::DS.$fileName);

        }
        
        $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
        $questionDocument = $dtoToDocumentConvertor->convertToDocument($dto);
        $this->dm->persist($questionDocument);
        $this->dm->flush();

        $messages[] = $this->translator->translate('The question have been created successfully!');
        return true;
    }

}
