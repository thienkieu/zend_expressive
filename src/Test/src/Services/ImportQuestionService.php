<?php

declare(strict_types=1);

namespace Test\Services;

use Infrastructure\Interfaces\HandlerInterface;

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

    private $readingType = 'Reading';
    private $listeningType = 'Listening';
    private $writingType = 'Writing';
    private $imageFiles = 'MediaQuestion';

    private $container;

    /**
     * Class constructor.
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    public function isHandler($dto) {
        return true;
    }

    public function importQuestion($dtoObject, & $dto, & $messages) {
        try {
            $translator = $this->container->get(\Config\AppConstant::Translator);

            $extractResult = $this->extractZipFile($dtoObject->media, $this->imageFiles);
            if (!$extractResult) {                
                $messages[] = $translator->translate('Can not extract media file, Please check format again.');
                return false;
            }

            if ( $xls = \SimpleXLSX::parse($dtoObject->file) ) {
                $rows = $xls->rows();
                $numberRows = count($rows);
                $lstQuestions = [];
                $rowIndex = 5;
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
                            $question = $this->buildReadingQuestion($row);                        
                            while(true){
                                $subQuestion = $this->buildSubQuestion($row);
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
                            $question = $this->buildListeningQuestion($row);                        
                            while(true){
                                $subQuestion = $this->buildSubQuestion($row);
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
                            $question = $this->buildWritingQuestion($row);                        
                            while(true){
                                $subQuestion = $this->buildSubQuestion($row);
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

                $messages[] = $translator->translate('Questions have been upload successfull.!');
                
                return true;
            } else {
                $messages[] = \SimpleXLSX::parseError();       
                return false;
            }
        } catch(\Exception $e) {
            
            $log = $this->container->get(\Config\AppConstant::Log);
            $log->debug('Caught exception: '. $e->getMessage());
            $log->debug($e->getTraceAsString());

            $messages[] =  $translator->translate('There is error when parsed excel file, Please check with admin');       
            return false;
        }

    }
    
    protected function buidGeneralQuestion($data, &$question) {
        $question->setContent($data[$this->content]);
        $question->setType($data[$this->type]);
        $question->setSource($data[$this->source]);
        $question->setSubType($data[$this->subType]);
    }

    protected function buildReadingQuestion($data){
        $readingQuestion = new \Test\Documents\Question\ReadingQuestionDocument();
        $this->buidGeneralQuestion($data, $readingQuestion);

        $content = $readingQuestion->getContent();
        $images = $data[$this->fileName];
        if (!empty($images)) {
            $images = \explode(',', $images);
            foreach ($images as $image) {
                $content = str_replace('['.trim($image,' ').']', '<img src="'.$this->imageFiles.'/'.$image.'"/>', $content);
            }
            
        }
        
        $readingQuestion->setContent($content);
        return $readingQuestion;
    }
    
    protected function buildListeningQuestion($data){
        $listeningQuestion = new \Test\Documents\Question\ListeningQuestionDocument();
        $listeningQuestion->setRepeat($data[$this->repeatTime]);
        $listeningQuestion->setPath($this->imageFiles.'/'.$data[$this->fileName]);

        $this->buidGeneralQuestion($data, $listeningQuestion);
        return $listeningQuestion;
    }

    protected function buildWritingQuestion($data){
        $writingQuestion = new \Test\Documents\Question\WritingQuestionDocument();
        $this->buidGeneralQuestion($data, $writingQuestion);
        return $writingQuestion;
    }

    protected function buildSubQuestion($data){
        $subQuestion = new \Test\Documents\Question\SubQuestionDocument();
        $subQuestion->setContent($data[$this->question]);
        
        $answers = $this->buildAnswers($data);
        $subQuestion->setAnswers($answers);
        
        return $subQuestion;
    }

    protected function buildAnswers($data){
        $numberColumns = count($data);
        $lstAnswers = [];

        $correctAnswers = (string)$data[$this->correctAnswers];
        $correctAnswers = explode(',', $correctAnswers);

        for ($i=$this->answer; $i < $numberColumns; $i++) { 
            if (!empty($data[$i])) {
                $answer = new \Test\Documents\Question\AnswerDocument();
                $answer->setContent($data[$i]);
                $answer->setOrder($i - $this->answer);

                if (in_array($i - $this->answer + 1, $correctAnswers)) {
                    $answer->setIsCorrect(true);
                }

                $lstAnswers[] = $answer;
            }
            
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
