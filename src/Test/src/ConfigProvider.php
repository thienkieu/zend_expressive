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
                Handlers\CreateTestHandler::class => \App\Factory\HandlerFactory::class,
                Handlers\CreateSectionHandler::class => \App\Factory\HandlerFactory::class,
                Validators\CreateSectionValidatorMiddleware::class => InvokableFactory::class,
                Middlewares\RequestToSectionDTOMiddleware::class => InvokableFactory::class,
                Services\SectionService::class => \Infrastructure\Factory\BaseFactory::class,
                //Services\SectionService::class => InvokableFactory::class,
            ],

            'aliases' => [
                Services\SectionServiceInterface::class => Services\SectionService::class
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
