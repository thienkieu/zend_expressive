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
            'templates'    => $this->getTemplates(),
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

                Validators\CreateSectionValidatorMiddleware::class => InvokableFactory::class,
                Middlewares\RequestToSectionDTOMiddleware::class => InvokableFactory::class,
                Services\SectionServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\TestService::class => \Infrastructure\Factory\BaseFactory::class,
                
                Services\AdvanceTestService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\SectionService::class => \Infrastructure\Factory\BaseFactory::class,
                Services\TestServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                
                Services\ImportQuestionServiceInterface::class => \Infrastructure\Factory\ServiceFactory::class,
                Services\ImportQuestionService::class => \Infrastructure\Factory\BaseFactory::class,
            ],

            'aliases' => [
                //Services\SectionServiceInterface::class => Services\SectionService::class,
                
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
