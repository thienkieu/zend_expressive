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
            $name === 'Verbal'
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
                $type->getName() === 'Other'
            ) {
                $type->setRenderName('Other');
            }
			
			if (
                $type->getName() === 'Verbal'
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
                    $q = $question->getQuestionInfo();
                    $q->setPlatform($platform);
                    $q->setUser($user);
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

    protected function isWritingOptionSection($section) {
        $questions = $section->getQuestions();
        if (count($questions) == 2) {
            $isAllWriting = true;
            foreach($questions as $question) {
                $q = $question->getQuestionInfo();
                if($q->getType()->getParentType()->getName() !== "Writing"){
                    $isAllWriting = false;
                    break;
                }
            }

            return $isAllWriting;
        }
        
        return false;
    }

    protected function migrationAddOptionSection() {
        $examRepo = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            foreach($sections as $section){
                if($this->isWritingOptionSection($section)) {
                    $section->setIsOption(true);
                    $section->setRequiredQuestion(1);
                }else {
                    $section->setIsOption(false);
                }
                
            }
        }

        $examResultRepo = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultDocument::class);
        $examResults = $examResultRepo->findAll();
        foreach($examResults as $examResult) {
            $test = $examResult->getTest();
            $sections = $test->getSections();
            foreach($sections as $section){
                if($this->isWritingOptionSection($section)) {
                    $section->setIsOption(true);
                    $section->setRequiredQuestion(1);
                }else {
                    $section->setIsOption(false);
                }
            }
        }

        $testRepo = $this->dm->getRepository(\Test\Documents\Test\BaseTestDocument::class);
        $tests = $testRepo->findAll();
        foreach($tests as $test) {
            $sections = $test->getSections();
            foreach($sections as $section){
                if($this->isWritingOptionSection($section)) {
                    $section->setIsOption(true);
                    $section->setRequiredQuestion(1);
                }else {
                    $section->setIsOption(false);
                }
            }
        }

        $testTemplateRepo = $this->dm->getRepository(\Test\Documents\Test\TestTemplateDocument::class);
        $tests = $testTemplateRepo->findAll();
        foreach($tests as $test) {
            $sections = $test->getSections();
            foreach($sections as $section){
                if($this->isWritingOptionSection($section)) {
                    $section->setIsOption(true);
                    $section->setRequiredQuestion(1);
                }else {
                    $section->setIsOption(false);
                }
            }
        }

    }

    public function addTestArchitectType() {
        $platformService = $this->container->get(\Test\Services\Interfaces\PlatformServiceInterface::class);
        $defaultPlatform = $platformService->getPlatformByName('TestArchitect'); 

        $typeDocument = new  \Test\Documents\Question\TypeDocument();
        $typeDocument->setPlatform($defaultPlatform);
        $typeDocument->setName('TA writing');
        $typeDocument->setIsManualScored(true);
        $typeDocument->setRenderName('Writing');
        $this->dm->persist($typeDocument);

        $cshaptypeDocument = new  \Test\Documents\Question\TypeDocument();
        $cshaptypeDocument->setPlatform($defaultPlatform);
        $cshaptypeDocument->setName('C#');
        $cshaptypeDocument->setIsManualScored(true);
        $cshaptypeDocument->setRenderName('Writing');
        $cshaptypeDocument->setParentType($typeDocument);
        $this->dm->persist($cshaptypeDocument);


        $javaTypeDocument = new  \Test\Documents\Question\TypeDocument();
        $javaTypeDocument->setPlatform($defaultPlatform);
        $javaTypeDocument->setName('Java');
        $javaTypeDocument->setIsManualScored(true);
        $javaTypeDocument->setRenderName('Writing');
        $javaTypeDocument->setParentType($typeDocument);
        $this->dm->persist($javaTypeDocument);

        $taOthertypeDocument = new  \Test\Documents\Question\TypeDocument();
        $taOthertypeDocument->setPlatform($defaultPlatform);
        $taOthertypeDocument->setName('TA other');
        $taOthertypeDocument->setIsManualScored(true);
        $taOthertypeDocument->setRenderName('Other');
        $this->dm->persist($taOthertypeDocument);

        $labTypeDocument = new  \Test\Documents\Question\TypeDocument();
        $labTypeDocument->setPlatform($defaultPlatform);
        $labTypeDocument->setName('Lab');
        $labTypeDocument->setIsManualScored(true);
        $labTypeDocument->setRenderName('Verbal');
        $labTypeDocument->setParentType($taOthertypeDocument);
        $this->dm->persist($labTypeDocument);

        $nonSubTypeDocument = new  \Test\Documents\Question\TypeDocument();
        $nonSubTypeDocument->setPlatform($defaultPlatform);
        $nonSubTypeDocument->setName('Non sub question');
        $nonSubTypeDocument->setIsManualScored(true);
        $nonSubTypeDocument->setRenderName('NonSub');
        $nonSubTypeDocument->setParentType($taOthertypeDocument);
        $this->dm->persist($nonSubTypeDocument);

        $taReadingtypeDocument = new  \Test\Documents\Question\TypeDocument();
        $taReadingtypeDocument->setPlatform($defaultPlatform);
        $taReadingtypeDocument->setName('TA reading');
        $taReadingtypeDocument->setIsManualScored(false);
        $taReadingtypeDocument->setRenderName('Reading');
        $this->dm->persist($taReadingtypeDocument);

        $taScriptTypeDocument = new  \Test\Documents\Question\TypeDocument();
        $taScriptTypeDocument->setPlatform($defaultPlatform);
        $taScriptTypeDocument->setName('TA script');
        $taScriptTypeDocument->setIsManualScored(false);
        $taScriptTypeDocument->setRenderName('Reading');
        $taScriptTypeDocument->setParentType($taReadingtypeDocument);
        $this->dm->persist($taScriptTypeDocument);

        $manualScriptTypeDocument = new  \Test\Documents\Question\TypeDocument();
        $manualScriptTypeDocument->setPlatform($defaultPlatform);
        $manualScriptTypeDocument->setName('Manual test script');
        $manualScriptTypeDocument->setIsManualScored(false);
        $manualScriptTypeDocument->setRenderName('Reading');
        $manualScriptTypeDocument->setParentType($taReadingtypeDocument);
        $this->dm->persist($manualScriptTypeDocument);
    }

    public function migration() {
        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $user = $authorizationService->getUser();

        $platformService = $this->container->get(\Test\Services\Interfaces\PlatformServiceInterface::class);
        $defaultPlatform = $platformService->getPlatformByName('English'); 

        $this->migrationAddOptionSection();
        $this->migrationTest($defaultPlatform, $user);
        $this->migrationExam($defaultPlatform, $user);

        $this->migrationQuestion($defaultPlatform, $user);
        
        $this->migrationTestTemplate($defaultPlatform, $user);
        
        $this->migrationExamResult($defaultPlatform, $user);
        $this->migrationQuestionType($defaultPlatform);
        
        $this->addTestArchitectType();

        $this->dm->flush();

        return true;
    }
}
