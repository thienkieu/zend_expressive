<?php

declare(strict_types=1);

namespace Test;

use Zend\ServiceManager\Factory\InvokableFactory; 
/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'authenticationExcludeUrl' => $this->getAuthenticationExcludeUrl(),
            'validatorRequestAdapters' => $this->getValidatorRequestAdapters(),
            'convertorDocumentToDTOAdapters' => $this->getConvertorDocumentToDTOAdapters(),
            'convertorDTOAdapters' => $this->getConvertorDTOAdapters(),
            'convertorDocumentAdapters' => $this->getConvertorDocumentAdapters(),
            'resolveService' => $this->getResolveService(),
            'requestToDTO' => $this->getRequestToDTOClass()
        ];
    }

    public function getRequestToDTOClass(): array {
        return [
            'section.create' => \Test\DTOs\SectionDTO::class,
            'test.create'   => \Test\DTOs\Test\BaseTestDTO::class,
            'test.createTemplate'   => \Test\DTOs\Test\BaseTestDTO::class,
            'test.update'   => \Test\DTOs\Test\BaseTestDTO::class,
            'question.source.create'   => \Test\DTOs\Question\SourceDTO::class,
            'question.source.update'   => \Test\DTOs\Question\SourceDTO::class,
            'question.source.delete'   => \Test\DTOs\Question\SourceDTO::class,
            'exam.create' => \Test\DTOs\Exam\ExamDTO::class,
            'exam.update' => \Test\DTOs\Exam\ExamDTO::class,
            'exam.updateTest' => \Test\DTOs\Exam\EditTestOfExamDTO::class,
            'exam.exams'    =>   \Test\DTOs\Exam\FilterExamDTO::class,
            'exam.exportExamResultSummary'    =>   \Test\DTOs\Exam\FilterExamDTO::class,
            'exam.listeningFinished'    =>   \Test\DTOs\ExamResult\ListeningQuestionListeningFinishedDTO::class,
            'exam.clickToListen'    =>   \Test\DTOs\ExamResult\ListeningQuestionClickToListenDTO::class,
            'exam.addResult'    =>   \Test\DTOs\ExamResult\ExamResultDTO::class,
            'test.viewSampleExam' => \Test\DTOs\Test\BaseTestDTO::class,
            'exam.updateAnswer' => \Test\DTOs\ExamResult\UserAnswerDTO::class,
            'user.updateAnswer' => \Test\DTOs\ExamResult\UserAnswerDTO::class,
            'exam.updateQuestionMark' => \Test\DTOs\ExamResult\UpdateQuestionMarkDTO::class,
            'exam.updateSectionMark' => \Test\DTOs\ExamResult\UpdateSectionMarkDTO::class,
            'question.type.create'  => \Test\DTOs\Question\TypeDTO::class,
            'questions.create'  => \Test\DTOs\Question\QuestionDTO::class,
            'questions.update'  => \Test\DTOs\Question\QuestionDTO::class,
            'platform.create'  => \Test\DTOs\PlatformDTO::class,
        ];
    }

    public function getResolveService(): array {
        return [
            \Test\Services\Interfaces\TestServiceInterface::class => [
                \Test\Services\TestService::class,
                \Test\Services\AdvanceTestService::class,        
            ], 
            \Test\Services\Interfaces\TestTemplateServiceInterface::class => [
                \Test\Services\TestTemplateService::class,       
            ], 
            \Test\Services\ImportQuestionServiceInterface::class => [
                \Test\Services\ImportQuestionService::class,
            ],
            \Test\Services\CandidateServiceInterface::class => [
                \Test\Services\CandidateService::class,
            ],
            \Test\Services\Interfaces\SourceServiceInterface::class => [
                \Test\Services\SourceService::class,
            ],
            \Test\Services\Interfaces\TypeServiceInterface::class => [
                \Test\Services\LogigearTypeService::class,
                \Test\Services\TypeService::class,
            ],
            \Test\Services\ExamServiceInterface::class => [
                \Test\Services\EmptyTestExamService::class,
                \Test\Services\VerbalExamService::class,
                \Test\Services\LogigearExamService::class,
                \Test\Services\ExamService::class,
            ],
            \Test\Services\DoExamServiceInterface::class => [
                \Test\Services\DoExamService::class,
            ],
            \Test\Services\Interfaces\ExportServiceInterface::class => [
                \Test\Services\ExportService::class,
            ],
            
            \Test\Services\Question\QuestionServiceInterface::class => [
                \Test\Services\Question\NonSubQuestionService::class,
                \Test\Services\Question\ReadingQuestionService::class,
                \Test\Services\Question\WritingQuestionService::class,
                \Test\Services\Question\ListeningQuestionService::class,
                \Test\Services\Question\VerbalQuestionService::class,
                \Test\Services\Question\QuestionService::class,
            ],
            \Test\Services\PinServiceInterface::class => [
                \Test\Services\PinService::class,
            ],
    
            \Test\Services\DoExamResultServiceInterface::class => [
                \Test\Services\DoExamResultNonSubService::class,
                \Test\Services\DoExamResultService::class,
                \Test\Services\DoExamResultWritingService::class,
                \Test\Services\DoExamResultRepeatTimesService::class,
                \Test\Services\DoExamResultListeningFinishService::class,
                \Test\Services\DoExamResultListeningClickToListenService::class,
                \Test\Services\DoBaseExamResultService::class,
            ],
            
            \Test\Services\TrackingConnectServiceInterface::class => [
                \Test\Services\TrackingConnectService::class
            ],
            \Test\Services\Interfaces\PlatformServiceInterface::class => [
                \Test\Services\PlatformService::class
            ],
            \Test\Services\Interfaces\MigrationServiceInterface::class => [
                \Test\Services\MigrationService::class
            ]
        ];
    }

    public function getConvertorDocumentAdapters(): array {
        return [
            \Test\Convertor\Adapter\Documents\ToListeningDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToReadingDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToWritingDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToVerbalDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToTestTemplateDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToTestDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToSubQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToSourceDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToSubTypeDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToTypeDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToPlatformDocumentAdapter::class,
            
            \Test\Convertor\Adapter\Documents\ToSectionDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\Test\ToRandomQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\Test\ToQuestionDocumentAdapter::class,
    
            \Test\Convertor\Adapter\Documents\ToExamDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToCandidateDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToExamResultDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToTestEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToListeningEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToVerbalEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToWritingEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToReadingEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToNonSubQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\Documents\ToNonSubQuestionEmbedDocumentAdapter::class,
            
            \Test\Convertor\Adapter\Documents\ExamResult\ToExamResultCandidateDocumentAdapter::class,
        ];
    }
    public function getConvertorDTOAdapters(): array {
        return [
            \Test\Convertor\Adapter\DTOs\ToListeningDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToReadingDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToVerbalDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToNonSubQuestionDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToWritingDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToSourceDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToTypeDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToPlatformDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToSubTypeDTOAdapter::class,
            
            \Test\Convertor\Adapter\DTOs\Test\ToTestTemplateSectionDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\ToTestWithSectionDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\ToSectionDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\ToQuestionDTOAdapter::class,  
            \Test\Convertor\Adapter\DTOs\Exam\ToExamHasSectionTestDTOAdapter::class,  
            \Test\Convertor\Adapter\DTOs\Exam\ToCandidateDTOAdapter::class,  
            \Test\Convertor\Adapter\DTOs\Exam\ToEditTestOfExamDTOAdapter::class,  
            \Test\Convertor\Adapter\DTOs\Exam\ToPinDTOAdapter::class,  
            \Test\Convertor\Adapter\DTOs\Exam\ToPinInfoDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToPickupAnswerDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToNonSubPickupAnswerDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToUpdateRepeatTimesDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\ToUpdateWritingAnswerDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\UpdateQuestionMarkDTOAdapter::class,
            \Test\Convertor\Adapter\DTOs\UpdateSectionMarkDTOAdapter::class,
    
            \ODMAuth\Convertor\Adapter\DTOs\ToAssignUserPermissionDTOAdapter::class,
    
            \Test\Convertor\Adapter\DTOs\ExamResult\ToExamResultHasSectionTestDTOAdapter::class,
        ];
    }

    public function getConvertorDocumentToDTOAdapters(): array {
        return [
            \Test\Convertor\Adapter\DTOs\FromListeningDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromReadingDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromWritingDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromVerbalDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromSubQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromSourceDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromTypeDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromPlatformDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromSubTypeDocumentAdapter::class,
            
            \Test\Convertor\Adapter\DTOs\FromNonSubQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromQuestionDocumentAdapter::class,
            
            
            \Test\Convertor\Adapter\DTOs\FromAnswerDocumentAdapter::class,
            
            \Test\Convertor\Adapter\DTOs\Test\FromQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromSectionTestDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromTestWithSectionDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromReadingEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromListeningEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromNonSubEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromWritingEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromVerbalEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\Test\FromRandomQuestionDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromExamResultSummaryDocumentAdapter::class,
            
            \Test\Convertor\Adapter\DTOs\FromCandidateDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromExamDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromTestWithSectionEmbedDocumentAdapter::class,
            \Test\Convertor\Adapter\DTOs\FromExamResultHasSectionTestDocumentAdapter::class,
    
            \Test\Convertor\Adapter\DTOs\Test\FromTestTemplateDocumentAdapter::class,
        ];
    }

    public function getValidatorRequestAdapters(): array {
        return [
            //\Test\Validator\CreateReadingSectionValidatorAdapter::class,
            \Test\Validator\CreateQuestionValidatorAdapter::class,        
            \Test\Validator\CreateTestWithSectionValidatorAdapter::class,
            \Test\Validator\CreateExamWithSectionValidatorAdapter::class,
            \Test\Validator\EnterPinValidatorAdapter::class,
            \Test\Validator\CreateSourceValidatorAdapter::class,
            \Test\Validator\UpdateMarkValidatorAdapter::class,
        ];
    }

    public function getAuthenticationExcludeUrl(): array 
    {
        return [
            'exam.doExam',
            'api.publickey',
            'home',
            'api.token',
            'exam.enterPin',
            'user.updateAnswer',
            'exam.synchronyTime',
            'exam.finish',
            'question.types',
            'question.sources',
            'exam.types',
            'exam.viewExamResult',
            'exam.exportCandidateResult',
            'exam.exportPin',
            'exam.examJoined',
            'exam.latestExamJoined',
            'question.importTemplate',
            'log',
            'exam.exportExamResultSummary'
        ];
    }
    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handlers\VerifyODMConfigHandler::class => \App\Factory\HandlerFactory::class,
                Handlers\CreateTestHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreateSectionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ImportQuestionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetCandidateHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetTestHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreateTestTemplateHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreateSourceHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DeleteSourceHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateSourceHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetSourceHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreateExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\EnterPinHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DoExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\RefreshPinHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateAnswerHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\SynchronyTimeHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\FinishExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateQuestionMarkHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateSectionMarkHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetQuestionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ViewExamResultHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreateTypeHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ViewSampleExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateTestOfExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ViewListExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DeleteTestHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DeleteTestTemplateHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DeleteExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CompleteExamHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ExportCandidateResultHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateTestHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DeleteQuestionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateQuestionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreateQuestionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ExportQuestionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\DownloadImportQuestionTemplateHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetTypeHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetTestTemplateHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UploadMediaHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\AddExamResultHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetExamJoinedHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetLatestJoinedHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ExportPinHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\ExportExamResultHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetExamTypeHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateListeningFinishHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateListeningClickToListenHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\CreatePlatformHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\GetPlatformHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\MigrationHandler::class => \Infrastructure\Factory\BaseFactory::class,

                Validators\CreateSectionValidatorMiddleware::class => InvokableFactory::class,
                Validators\CreateQuestionValidatorAdapter::class => InvokableFactory::class,
                Validators\UpdateQuestionAnswerValidatorAdapter::class => InvokableFactory::class,
                Middlewares\RequestToSectionDTOMiddleware::class => InvokableFactory::class,
                Services\TestService::class => \Infrastructure\Factory\BaseFactory::class,
                
                Services\TestTemplateService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Interfaces\TestTemplateServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                
                Services\PlatformService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Interfaces\PlatformServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,

                Services\MigrationService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Interfaces\MigrationServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,

                Services\AdvanceTestService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Interfaces\TestServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                
                Services\ImportQuestionServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\ImportQuestionService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\CandidateServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\CandidateService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\Interfaces\SourceServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\SourceService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\Interfaces\TypeServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\TypeService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\LogigearTypeService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\Interfaces\ExportServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\ExportService::class => \Infrastructure\Factory\BaseFactory::class,

                
                Services\DoExamResultListeningService::class => \Infrastructure\Factory\BaseFactory::class,


                Services\ExamServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\VerbalExamService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\ExamService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\LogigearExamService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\EmptyTestExamService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\DoExamServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\DoExamService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\Question\QuestionServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\Question\QuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\WritingQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\NonSubQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\ReadingQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\ListeningQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\VerbalQuestionService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\PinServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\PinService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\DoExamResultServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\DoExamResultNonSubService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultListeningFinishService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultListeningClickToListenService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultWritingService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultRepeatTimesService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoBaseExamResultService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\TrackingConnectServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\TrackingConnectService::class => \Infrastructure\Factory\BaseFactory::class,
            ],

            'aliases' => [
                //Services\SectionServiceInterface::class => Services\SectionService::class,
                
            ],
        ];
    }
}
