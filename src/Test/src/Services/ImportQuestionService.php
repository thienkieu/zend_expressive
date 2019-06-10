<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;
use Test\Exceptions\ImportQuestionException;
use Test\Services\SourceServiceInterface;

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

    /**
     * Class constructor.
     */
    public function __construct($container = null)
    {
        $this->container = $container;
        $this->translator = $this->container->get(\Config\AppConstant::Translator);
        $this->sourceService = $this->container->get(SourceServiceInterface::class);        
    }

    public function isHandler($dto) {
        return true;
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
            
            if ( $xls = \SimpleXLSX::parse($dtoObject->file) ) {
                $rows = $xls->rows();
                $numberRows = count($rows);
                if ($numberRows <= $this->rowDataIndex || !$this->isCorrectColumns($rows[$this->rowDataIndex - 1])) {
                    $messages[] = $this->translator->translate('File is not correct format.', ['%file%' => basename($dtoObject->file)]);
                    return false;
                }


                $lstQuestions = [];
                $rowIndex = $this->rowDataIndex;
                $dm = $this->container->get(\Config\AppConstant::DocumentManager);
                
                while($rowIndex < $numberRows) {
                    $row = $rows[$rowIndex];
                    if (!isset($row[$this->id])) {
                        $rowIndex += 1;
                        continue;
                    }

                    $previousId = $row[$this->id];
                    
                    switch($row[$this->type]) {
                        case $this->readingType:        
                            $question = $this->buildReadingQuestion($row, $rowIndex + 1);
                            if ($question )                        
                            while(true){
                                $subQuestion = $this->buildSubQuestion($row, $rowIndex + 1);
                                $question->addSubQuestion($subQuestion);

                                $rowIndex += 1;
                                $row = $rows[$rowIndex];
                                if($row[$this->id] !== $previousId) {
                                    break;
                                }
                            }
                            
                            $dm->persist($question);
                            continue;                        
                        break;

                        case $this->listeningType: 
                            $question = $this->buildListeningQuestion($row, $rowIndex + 1);                        
                            while(true){
                                $subQuestion = $this->buildSubQuestion($row, $rowIndex + 1);
                                $question->addSubQuestion($subQuestion);

                                $rowIndex += 1;
                                $row = $rows[$rowIndex];
                                if($row[$this->id] !== $previousId) {
                                    break;
                                }
                            }
                            $dm->persist($question);
                            continue;   
                        break;

                        case $this->writingType: 
                            $question = $this->buildWritingQuestion($row, $rowIndex + 1);                        
                            while(true){
                                $subQuestion = $this->buildSubQuestion($row, $rowIndex + 1);
                                $question->addSubQuestion($subQuestion);

                                $rowIndex += 1;
                                $row = $rows[$rowIndex];
                                if($row[$this->id] !== $previousId) {
                                    break;
                                }
                            }
                            $dm->persist($question);
                            continue;   
                        break;

                        default:
                            $rowIndex += 1;
                        break;
                    }
                    
                }
                $dm->flush();

                $messages[] = $this->translator->translate('Questions have been upload successfull.!');
                
                return true;
            } else {
                $messages[] = \SimpleXLSX::parseError();       
                return false;
            }
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

    protected function buidGeneralQuestion($data, &$question) {
        $question->setContent($data[$this->content]);
        $question->setType($data[$this->type]);
        $question->setSource($data[$this->source]);
        $question->setSubType($data[$this->subType]);

        $isExistSource = $this->sourceService->isExistSourceName($data[$this->source], $messages);
        if (!$isExistSource) {
            $error = $this->translator->translate('Source is not exist.', ['%sourceName%'=> $data[$this->source]]);
            throw new ImportQuestionException($error);
        }
    }

    protected function buildReadingQuestion($data, $lineNumber){
        $readingQuestion = new \Test\Documents\Question\ReadingQuestionDocument();
        $this->buidGeneralQuestion($data, $readingQuestion);

        $content = $readingQuestion->getContent();
        if (empty($content)) {
            $translatorParams = [
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Question content can not empty', $translatorParams);
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

                $content = str_replace('['.trim($image,' ').']', '<img src="'.$this->imageFiles.'/'.$image.'"/>', $content);
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

        $realPath = realpath($this->imageFiles);
        if (!file_exists($realPath.'/'.$data[$this->fileName])) {
            $error = $this->translator->translate('File name is not exist.', ['%fileName%'=> $data[$this->fileName]]);
            throw new ImportQuestionException($error);
        }
        
        $listeningQuestion = new \Test\Documents\Question\ListeningQuestionDocument();
        $listeningQuestion->setRepeat($data[$this->repeatTime]);
        $listeningQuestion->setPath($this->imageFiles.'/'.$data[$this->fileName]);

        $this->buidGeneralQuestion($data, $listeningQuestion);
        return $listeningQuestion;
    }

    protected function buildWritingQuestion($data, $lineNumber){
        $writingQuestion = new \Test\Documents\Question\WritingQuestionDocument();
        $this->buidGeneralQuestion($data, $writingQuestion);
        $content = $writingQuestion->getContent();
        if (empty($content)) {
            $translatorParams = [
                '%lineNumber%' => $lineNumber
            ];
            $error = $this->translator->translate('Question content can not empty', $translatorParams);
            throw new ImportQuestionException($error);
        }

        return $writingQuestion;
    }

    protected function buildSubQuestion($data, $rowIndex){
        $subQuestion = new \Test\Documents\Question\SubQuestionDocument();
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
