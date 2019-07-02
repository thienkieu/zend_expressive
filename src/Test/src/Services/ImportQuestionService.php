<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;
use Test\Exceptions\ImportQuestionException;
use Test\Services\Interfaces\SourceServiceInterface;
use Test\Services\Interfaces\TypeServiceInterface;
use Infrastructure\DataParser\DataParserInterface;

class ImportQuestionService implements ImportQuestionServiceInterface, HandlerInterface
{
    private $id = 1;
    private $type = 2;
    private $subType = 3;
    private $source = 4;
    private $fileName = 5;
    private $repeatTime = 6;
    private $content = 7;
    private $question = 8;
    private $correctAnswers = 9;
    private $answer = 10;
    private $rowDataIndex = 5;
    
    private $readingType = \Config\AppConstant::Reading;
    private $listeningType = \Config\AppConstant::Listening;
    private $writingType = \Config\AppConstant::Writing;
    private $imageFiles = 'MediaQuestion';
    
    private $container;
    private $translator;
    private $sourceService;
    private $typService;
    private $dataParser;
    private $rowIndex = 0;

    /**
     * Class constructor.
     */
    public function __construct($container = null)
    {
        $this->container = $container;
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
        $this->sourceService = $this->container->get(SourceServiceInterface::class);  
        $this->typeService = $this->container->get(TypeServiceInterface::class);       
        $this->dataParser = $this->container->build(DataParserInterface::class, [DataParserInterface::FileTypeKey => 'excel']);
    }

    public function isHandler($dto, $options = []) {
        return true;
    }

    protected function processContent($data) {

    }

    private function getNextRow() {
        $row = null;
        $this->rowIndex += 1;
        $this->dataParser->next();
        if ($this->dataParser->valid()){
            $row = $this->dataParser->current();
        }

        return $row;
    }

    public function importQuestion($dtoObject, & $dto, & $messages) {
        try {
            
            if (isset($dtoObject->media)) {
                $this->imageFiles = \Config\AppConstant::MediaQuestionFolder . \Config\AppConstant::DS.date('Ymd');
                if (!file_exists($this->imageFiles)) {
                    mkdir($this->imageFiles, 0777, true);
                }

                $extractResult = $this->extractZipFile($dtoObject->media, $this->imageFiles);
                if (!$extractResult) {                
                    $messages[] = $this->translator->translate('Can not extract media file, Please check format again.');
                    return false;
                }
            }
            
            $this->dataParser->parseData($dtoObject, ['rowIndexStart' => $this->rowDataIndex]);
            
    
            $lstQuestions = [];
            $this->rowIndex = $this->rowDataIndex;
            $dm = $this->container->get(\Config\AppConstant::DocumentManager);                
            while($this->dataParser->valid()) {
                $row = $this->dataParser->current();
                $previousId = $row[$this->id];
                
                switch($row[$this->type]) {
                    case $this->readingType:        
                        $question = $this->buildReadingQuestion($row, $this->rowIndex + 1);
                        if ($question )                        
                        while(true){
                            $subQuestion = $this->buildSubQuestion($row, $this->rowIndex + 1);
                            $question->addSubQuestion($subQuestion);
                            
                            $row = $this->getNextRow();                            
                            if(!$row || $row[$this->id] !== $previousId) {
                                break;
                            }
                        }
                        
                        $dm->persist($question);
                        continue;                        
                    break;

                    case $this->listeningType: 
                        $question = $this->buildListeningQuestion($row, $this->rowIndex + 1);                        
                        while(true){
                            $subQuestion = $this->buildSubQuestion($row, $this->rowIndex + 1);
                            $question->addSubQuestion($subQuestion);

                            $row = $this->getNextRow();                            
                            if(!$row || $row[$this->id] !== $previousId) {
                                break;
                            }
                        }
                        $dm->persist($question);
                        continue;   
                    break;

                    case $this->writingType: 
                        $question = $this->buildWritingQuestion($row, $this->rowIndex + 1);                        
                        $row = $this->getNextRow(); 
                        
                        $dm->persist($question);
                        continue;   
                    break;

                    default:
                        $row = $this->getNextRow(); 
                    break;
                }
                
            }
            $dm->flush();

            $messages[] = $this->translator->translate('Questions have been upload successfull.!');
            
            return true;
            
        } catch(ImportQuestionException $e) {
            $messages[] =  $e->getMessage();       
            return false;                        
        } catch(\Exception $e) {
            
            $log = $this->container->get(\Config\AppConstant::Log);
            $log->debug('Caught exception: '. $e->getMessage());
            $log->debug($e->getTraceAsString());

            $messages[] =  $this->translator->translate('There is error when parsed excel file, Please check with admin');       
            return false;
        }

    }
    
    protected function isCorrectColumns($columns) {
        if ($columns[$this->id] !== '#' || $columns[$this->type] !== 'Type' || $columns[$this->subType] !== 'Sub-Type'
            || $columns[$this->source] !== 'Source' || $columns[$this->fileName] !== 'File Name' || $columns[$this->repeatTime] !== 'Repeat Time'
            || $columns[$this->content] !== 'Content' || $columns[$this->question] !== 'Question' || $columns[$this->correctAnswers] !== 'Correct Answers'
            || $columns[$this->answer] !== 'Answer 1'
        ) {
            return false;
        }

        return true;
    }

    protected function buidGeneralQuestion($data, &$question, $lineNumber) {
        $this->validSource($data[$this->source], $question, $lineNumber);
        $this->validType($data[$this->type], $data[$this->subType], $question, $lineNumber);

        $question->setContent($data[$this->content]);
        $question->setType($data[$this->type]);
        $question->setSource($data[$this->source]);
        $question->setSubType($data[$this->subType]);

    }

    protected function validSource($source, &$question, $lineNumber) {
        $source = trim($source, ' ');
        if (empty($source)) {
            $error = $this->translator->translate('Source cannot empty.', ['%lineNumber%'=> $lineNumber]);
            throw new ImportQuestionException($error);
        }

        $isExistSource = $this->sourceService->isExistSourceName($source, $messages);
        if (!$isExistSource) {
            $error = $this->translator->translate('Source is not exist.', ['%sourceName%'=> $source]);
            throw new ImportQuestionException($error);
        }
    }

    protected function validType($type, $subType, &$question, $lineNumber) {
        $type = trim($type, ' ');
        if (empty($type)) {
            $error = $this->translator->translate('Type cannot empty.', ['%lineNumber%'=> $lineNumber]);
            throw new ImportQuestionException($error);
        }

        $isExistType = $this->typeService->isExistTypeName($type);
        if (!$isExistType) {
            $error = $this->translator->translate('Type is not exist.', ['%typeName%'=> $type, '%lineNumber%'=> $lineNumber]);
            throw new ImportQuestionException($error);
        }

        $subType = trim($subType, ' ');
        if (empty($type)) {
            $error = $this->translator->translate('SubType cannot empty.', ['%lineNumber%'=> $lineNumber]);
            throw new ImportQuestionException($error);
        }

        $isExistSubType = $this->typeService->isExistSubTypeName($type, $subType);
        if (empty($isExistSubType)) {
            $error = $this->translator->translate('SubType is not exist.', ['%subType%'=> $subType, '%lineNumber%'=> $lineNumber]);
            throw new ImportQuestionException($error);
        }

    }

    protected function buildReadingQuestion($data, $lineNumber){
        $readingQuestion = new \Test\Documents\Question\ReadingQuestionDocument();
        $this->buidGeneralQuestion($data, $readingQuestion, $lineNumber);

        $content = $readingQuestion->getContent();
        if (empty($content)) {
            $translatorParams = [
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Content content can not empty', $translatorParams);
            throw new ImportQuestionException($error);
        }

        $images = $data[$this->fileName];

        if (!empty($images)) {
            $images = \explode(',', $images);
            foreach ($images as $image) {
                $realPath = realpath($this->imageFiles);

                if (!file_exists($realPath.'/'.$image)) {
                    $error = $this->translator->translate('File name is not exist.', ['%fileName%'=> $image]);
                    throw new ImportQuestionException($error);
                }

                $fileExtension = explode('.', $image);
                $isSupportFileType = $this->isSupportMediaFile($fileExtension[count($fileExtension) -1], \Config\AppConstant::ImageExtension);
                
                if (!$isSupportFileType) {
                    $error = $this->translator->translate('File type %fileName% is not support.', ['%fileName%'=> $fileExtension[count($fileExtension) -1]]);
                    throw new ImportQuestionException($error);
                }

                $content = str_replace('['.trim($image,' ').']', '<div class="online-test-question-image"><img src="'.\Config\AppConstant::HOST_REPLACE.'/'.$this->imageFiles.'/'.$image.'"/></div>', $content);
            }
            
        }
        
        $readingQuestion->setContent($content);
        return $readingQuestion;
    }
    
    protected function buildListeningQuestion($data, $lineNumber){
        if (empty($data[$this->fileName])) {
            $translatorParams = [
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Listening must have radio file at question', $translatorParams);
            throw new ImportQuestionException($error);
        }

        $repeatTime = 0;
        if (!is_numeric($data[$this->repeatTime])) {
            $error = $this->translator->translate('the Repeate time must be number', $translatorParams);
            throw new ImportQuestionException($error);
        }else {
            $repeatTime = (int)$data[$this->repeatTime];
        }


        if ($repeatTime < 1) {
            $translatorParams['%smaller%'] = \Config\AppConstant::MaximunRepeateTime;
            $translatorParams['%larger%'] = \Config\AppConstant::MinimunRepeateTime;
            $translatorParams['%lineNumber%'] =$lineNumber;

            $error = $this->translator->translate('the Repeate time must be larger %larger% and smaller %smaller%', $translatorParams);
            throw new ImportQuestionException($error);
        }

        $realPath = realpath($this->imageFiles);
        if (!file_exists($realPath.'/'.$data[$this->fileName])) {
            $error = $this->translator->translate('File name is not exist.', ['%fileName%'=> $data[$this->fileName]]);
            throw new ImportQuestionException($error);
        }
        $fileExtension = explode('.', $data[$this->fileName]);
        $isSupportFileType = $this->isSupportMediaFile($fileExtension[count($fileExtension) -1], \Config\AppConstant::RadioExtensions);
        
        if (!$isSupportFileType) {
            $error = $this->translator->translate('File type %fileName% is not support.', ['%fileName%'=> $fileExtension[count($fileExtension) -1]]);
            throw new ImportQuestionException($error);
        }
        $listeningQuestion = new \Test\Documents\Question\ListeningQuestionDocument();
        $listeningQuestion->setRepeat($data[$this->repeatTime]);
        $listeningQuestion->setPath('/'.$this->imageFiles.'/'.$data[$this->fileName]);

        $this->buidGeneralQuestion($data, $listeningQuestion, $lineNumber);
        return $listeningQuestion;
    }

    protected function isSupportMediaFile($fileType, $type){
        $config = $this->container->get(\Config\AppConstant::AppConfig);
        $uploadConfig = $config[\Config\AppConstant::UploadConfigName];
        $extensions = $uploadConfig[\Config\AppConstant::UploadExtensions];
        return \in_array($fileType, $extensions[$type]);
    }

    protected function buildWritingQuestion($data, $lineNumber){
        $writingQuestion = new \Test\Documents\Question\WritingQuestionDocument();
        $this->buidGeneralQuestion($data, $writingQuestion, $lineNumber);
        $content = $writingQuestion->getContent();
        if (empty($content)) {
            $translatorParams = [
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Content content can not empty', $translatorParams);
            throw new ImportQuestionException($error);
        }

        return $writingQuestion;
    }

    protected function buildSubQuestion($data, $rowIndex){
        $subQuestion = new \Test\Documents\Question\SubQuestionDocument();
        $questionContent =  $data[$this->question];
        if (empty($questionContent)) {
            $translatorParams = [
                '%lineNumber%' => $rowIndex
            ];
            $error = $this->translator->translate('Question content can not empty', $translatorParams);
            throw new ImportQuestionException($error);
        }

        $subQuestion->setContent($data[$this->question]);
        
        $answers = $this->buildAnswers($data, $rowIndex);
        $subQuestion->setAnswers($answers);
        
        return $subQuestion;
    }

    protected function hasValueAfterColumn($data, $column) {
        $numberColumns = count($data);
        for ($i= $column; $i < $numberColumns ; $i++) { 
            if (!empty($data[$i])) return true;
        }

        return false;
    }

    protected function buildAnswers($data, $lineNumber){
        $numberColumns = count($data);
        $lstAnswers = [];

        $correctAnswerString = (string)$data[$this->correctAnswers];
        if (empty($correctAnswerString) && $data[$this->type] !== $this->writingType) {
            $translatorParams = [
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Correct answer can not empty at question', $translatorParams);
            throw new ImportQuestionException($error);
        }

        $correctAnswers = explode(',', $correctAnswerString);
        $isFirstAnswer = true;
        $numberCorrectAnswer = 0;

        for ($i=$this->answer; $i < $numberColumns; $i++) { 
            if(empty($data[$i]) && (($isFirstAnswer && $data[$this->type] !== $this->writingType) || $this->hasValueAfterColumn($data, $i+1))) {
                $translatorParams = [
                    '%answerIndex%'=> $i - $this->answer + 1, 
                    '%lineNumber%' => $lineNumber
                ];

                $error = $this->translator->translate('Answer can not empty at question', $translatorParams);
                throw new ImportQuestionException($error);
            }
            
            if (!empty($data[$i])) {
                $isFirstAnswer = false;
                $answer = new \Test\Documents\Question\AnswerDocument();
                $answer->setContent($data[$i]);
                $answer->setOrder($i - $this->answer);

                if (in_array($i - $this->answer + 1, $correctAnswers)) {
                    $answer->setIsCorrect(true);
                    $numberCorrectAnswer++;
                }
                
                $lstAnswers[] = $answer;                
            }
            
        }

        if ($numberCorrectAnswer != count($correctAnswers) && $data[$this->type] !== $this->writingType) {
            $translatorParams = [
                '%correctAnswer%' => $correctAnswerString,
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Correct answer is incorrect. There is not answer at question', $translatorParams);
            throw new ImportQuestionException($error);
        }

        return $lstAnswers;
    }

    protected function extractZipFile($file, $path) {
        $realPath = realpath($path);
        $zip = new \ZipArchive();
        $res = $zip->open($file);
        if ($res === TRUE) {
            $zip->extractTo($realPath);
            $zip->close();
            return true;
        }

        return false;
    }
}
