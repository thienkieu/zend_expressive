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
            'authenticationExcludeUrl' => $this->getAuthenticationExcludeUrl()
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
            'exam.updateAnswer',
            'exam.synchronyTime',
            'exam.finish',
            'question.types',
            'question.sources',
            'exam.types',
            'exam.viewExamResult',
            'question.export',
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
                Handlers\UpdateDisconnectHandler::class => \Infrastructure\Factory\BaseFactory::class,
                Handlers\UpdateTimeoutHandler::class => \Infrastructure\Factory\BaseFactory::class,

                Validators\CreateSectionValidatorMiddleware::class => InvokableFactory::class,
                Validators\CreateQuestionValidatorAdapter::class => InvokableFactory::class,
                Middlewares\RequestToSectionDTOMiddleware::class => InvokableFactory::class,
                Services\TestService::class => \Infrastructure\Factory\BaseFactory::class,
                
                Services\TestTemplateService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Interfaces\TestTemplateServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                

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

                Services\DoExamResultListeningServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
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
                Services\Question\ReadingQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\ListeningQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\Question\VerbalQuestionService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\PinServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\PinService::class => \Infrastructure\Factory\BaseFactory::class,

                Services\DoExamResultServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\DoExamResultService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultListeningFinishService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultListeningClickToListenService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultWritingService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoExamResultRepeatTimesService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\DoBaseExamResultService::class => \Infrastructure\Factory\BaseFactory::class,
            ],

            'aliases' => [
                //Services\SectionServiceInterface::class => Services\SectionService::class,
                
            ],
        ];
    }
}
