<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class MigrationService implements Interfaces\MigrationServiceInterface, HandlerInterface
{
    protected $container;
    protected $dm;
    protected $options;
    protected $platforms = null;
    protected $translator;

    public function __construct($container, $options) {
        $this->container = $container;
        $this->options = $options;
        $this->dm = $this->container->get('documentManager');
        $this->translator = $this->container->get(\Config\AppConstant::Translator);;
    }

    public function isHandler($param, $options = []){
        return true;
    }

    protected function migrationQuestion($platform, $user) {
        $questionReposity = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questions = $questionReposity->findAll();
        foreach($questions as $question) {
            $question->setPlatform($platform);
            $question->setUser($user);
        }
    }

    protected function getRenderTypeByTypeName($name) {
        
        if (
            $name === 'Paired reading' ||
            $name === 'Article' || 
            $name === 'Reading'
        ) {
           return 'Reading';
        }

        if (
            $name === 'Short speech' ||
            $name === 'Conversation' || 
            $name === 'Listening'
        ) {
            return 'Listening';
        }

        if (
            $name === 'Essay' ||
            $name === 'Email' || 
            $name === 'Writing'
        ) {
            return 'Writing';
        }

        if (
            $name === 'Verbal' || 
            $name === 'Other'
        ) {
            return 'Verbal';
        }

        if (
            $name === 'Other'
        ) {
            return 'Other';
        }
    }

    protected function migrationQuestionType($platform) {
        $typeReposity = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);
        $types = $typeReposity->findAll();
        foreach($types as $type) {
            $type->setPlatform($platform);
            if (
                $type->getName() === 'Paired reading' ||
                $type->getName() === 'Article' || 
                $type->getName() === 'Reading'
            ) {
                $type->setRenderName('Reading');
            }

            if (
                $type->getName() === 'Short speech' ||
                $type->getName() === 'Conversation' || 
                $type->getName() === 'Listening'
            ) {
                $type->setRenderName('Listening');
            }

            if (
                $type->getName() === 'Essay' ||
                $type->getName() === 'Email' || 
                $type->getName() === 'Writing'
            ) {
                $type->setRenderName('Writing');
            }

            if (
                $type->getName() === 'Verbal' || 
                $type->getName() === 'Other'
            ) {
                $type->setRenderName('Verbal');
            }

            
            //$type->setUser($user);
        }
    }

    protected function migrationTest($platform, $user) {
        $testRepo = $this->dm->getRepository(\Test\Documents\Test\BaseTestDocument::class);
        $tests = $testRepo->findAll();
        foreach($tests as $test) {
            $sections = $test->getSections();
            $test->setUser($user);
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    $q = $question->getQuestionInfo();
                    $q->setPlatform($platform);
                    $q->setUser($user);
                    
                }
            }
        }
    }

    protected function migrationTestTemplate($platform, $user) {
        $templateRepo = $this->dm->getRepository(\Test\Documents\Test\TestTemplateDocument::class);
        $templates = $templateRepo->findAll();
        foreach($templates as $template) {
            $sections = $template->getSections();
            $template->setUser($user);
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setPlatform($platform);
                        $q->setUser($user);
                    }
                }
            }
        }
    }

    protected function migrationExam($platform, $user) {
        $examRepo = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            $exam->setUser($user);
            $test->setUser($user);
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    $question->getGenerateFrom();
                    $q = $question->getQuestionInfo();
                    $q->setPlatform($platform);
                    $q->setUser($user);
                    
                }
            }
        }

    }

    protected function migrationExamResult($platform, $user) {
        $examRepo = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            $exam->setUser($user);
            $test->setUser($user);
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setPlatform($platform);
                        $q->setUser($user);
                    }
                }
            }
        }

    }

    public function migration() {
        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $user = $authorizationService->getUser();

        $platformService = $this->container->get(\Test\Services\Interfaces\PlatformServiceInterface::class);
        $defaultPlatform = $platformService->getPlatformByName('English'); 

        $this->migrationTest($defaultPlatform, $user);
        $this->migrationExam($defaultPlatform, $user);

        /*$this->migrationQuestion($defaultPlatform, $user);
        
        $this->migrationTestTemplate($defaultPlatform, $user);
        
        $this->migrationExamResult($defaultPlatform, $user);
        $this->migrationQuestionType($defaultPlatform);
        */

        $this->dm->flush();

        return true;
    }
}
