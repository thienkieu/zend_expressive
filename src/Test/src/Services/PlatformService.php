<?php

declare(strict_types=1);

namespace Test\Services;

use Zend\Log\Logger;

use Infrastructure\Convertor\DTOToDocumentConvertorInterface;
use Infrastructure\Convertor\DocumentToDTOConvertorInterface;
use Infrastructure\Interfaces\HandlerInterface;

class PlatformService implements Interfaces\PlatformServiceInterface, HandlerInterface
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
        /*if (!$this->types) {
            $typeRepository = $this->dm->getRepository(\Test\Documents\Question\TypeDocument::class);  
            $typeDocuments = $typeRepository->findAll();

            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $this->types = [];
            foreach ($typeDocuments as $type) {
                $dto = $documentToDTOConvertor->convertToDTO($type);
                $this->types[$dto->getName()] = $dto;
                $this->types[$dto->getId()] = $dto;
            }

        }*/
    }

    public function isHandler($param, $options = []){
        return true;
    }

    private function standardizedName($platformName) {
        return preg_replace('/\s\s+/', ' ', trim(strtoupper($platformName), ' '));
    }

    public function getPlatformByName($parentName, $subPlatformName = '') {
        if ($this->platforms && isset($this->platforms[$parentName])) {
            if (empty($subPlatformName)) $this->platforms[$parentName];
            if (isset($this->platforms[$subPlatformName])) {
                return $this->platforms[$subPlatformName];
            }            
        }

        $platformRepository = $this->dm->getRepository(\Test\Documents\PlatformDocument::class);  
        return $platformRepository->getPlatformByName($parentName, $subPlatformName);
    }

    public function getPlatformById($id) {
        if ($this->platforms && isset($this->platforms[$id])) {
            $this->platforms[$id];
        }

        $platformRepository = $this->dm->getRepository(\Test\Documents\PlatformDocument::class);  
        return $platformRepository->find($id);
    }
        
    public function getAllPlatforms(& $ret) {
        $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

        $platformRepository = $this->dm->getRepository(\Test\Documents\PlatformDocument::class);  
        $platformDocuments = $platformRepository->findAll();

        $ret = [];
        foreach ($platformDocuments as $platform) {
            $dto = $documentToDTOConvertor->convertToDTO($platform);
            $ret[] = $dto;
        }
        
        return true;
    }

    public function getPlatforms($content, & $messages, $pageNumber = 1, $itemPerPage = 25) {
        $platforms = [];
        if (!$this->platforms) {
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);

            $platformRepository = $this->dm->getRepository(\Test\Documents\PlatformDocument::class);  
            $platformDocuments = $platformRepository->findParentPlatform();
            
            
            foreach($platformDocuments as $parent) {
                $platforms[] = $documentToDTOConvertor->convertToDTO($parent);            
            }
            $this->platforms = $platforms;
        }
        
        $platforms = $this->platforms;
        
        $ret = new \stdClass();
        $ret->platforms = $platforms;
        $ret->pageNumber = $pageNumber;
        $ret->itemPerPage = $itemPerPage;
        
        return $ret;
    }

    public function createPlatform($dto, & $returnDTO, & $messages) {
        $messages = [];
        $translator = $this->container->get(\Config\AppConstant::Translator);
        
        $isExistedPlatform = $this->getPlatformByName($dto->getName());        
        if ($isExistedPlatform) {
            $messages[] = $translator->translate('Platform is existed, Please check your spelling again!');
            return false;
        }

        $parentPlatform = null;
        $parentName = $dto->getParentName();
        if (!empty($parentName)) {
            $parentPlatform = $this->getPlatformByName($parentName);
            if (!$parentPlatform) {
                $messages[] = $translator->translate('Platform isnot existed, Please check your spelling again!');
                return false;
            }
        }

        try{
            $dtoToDocumentConvertor = $this->container->get(DTOToDocumentConvertorInterface::class);
            $document = $dtoToDocumentConvertor->convertToDocument($dto);
            
            $document->setParentPlatform($parentPlatform);
            
            $this->dm->persist($document);
            $this->dm->flush();
            
            $documentToDTOConvertor = $this->container->get(DocumentToDTOConvertorInterface::class);
            $returnDTO = $documentToDTOConvertor->convertToDTO($document);

            $messages[] = $translator->translate('Your platform have been created successfully!');
            return true;
        } catch(\Exception $e){
            $messages[] = $translator->translate('There is error with create platform, Please check admin site');
           
            $logger = $this->container->get(Logger::class);
            $logger->info($e);
            
            return false;
        }

               
    }

    public function migrationPlatformForQuestion() {
        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $user = $authorizationService->getUser();

        $defaultPlatform = $this->getPlatformByName('English'); 
        $questionReposity = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questions = $questionReposity->findAll();
        foreach($questions as $question) {
            $question->setPlatform($defaultPlatform);
        }

        $testRepo = $this->dm->getRepository(\Test\Documents\Test\BaseTestDocument::class);
        $tests = $testRepo->findAll();
        foreach($tests as $test) {
            $sections = $test->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setPlatform($defaultPlatform);
                    }
                }
            }
        }

        $templateRepo = $this->dm->getRepository(\Test\Documents\Test\TestTemplateDocument::class);
        $templates = $templateRepo->findAll();
        foreach($templates as $template) {
            $sections = $template->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setPlatform($defaultPlatform);
                        $q->setUser($user);
                    }
                }
            }
        }

        $examRepo = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setPlatform($defaultPlatform);
                    }
                }
            }
        }

        $examRepo = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setPlatform($defaultPlatform);
                    }
                }
            }
        }

        
        
        $questionReposity = $this->dm->getRepository(\Test\Documents\Question\QuestionDocument::class);
        $questions = $questionReposity->findAll();
        foreach($questions as $question) {
            $question->setUser($user);
        }

        $testRepo = $this->dm->getRepository(\Test\Documents\Test\BaseTestDocument::class);
        $tests = $testRepo->findAll();
        foreach($tests as $test) {
            $sections = $test->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setUser($user);
                    }
                }
            }
        }

        $examRepo = $this->dm->getRepository(\Test\Documents\Exam\ExamDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setUser($user);
                    }
                }
            }
        }

        $examRepo = $this->dm->getRepository(\Test\Documents\ExamResult\ExamResultDocument::class);
        $exams = $examRepo->findAll();
        foreach($exams as $exam) {
            $test = $exam->getTest();
            $sections = $test->getSections();
            foreach($sections as $section){
                $questions = $section->getQuestions();
                foreach($questions as $question) {
                    if ($question->getGenerateFrom() === 'pickup') {
                        $q = $question->getQuestionInfo();
                        $q->setUser($user);
                    }
                }
            }
        }

        $this->dm->flush();

        return true;
    }
}
