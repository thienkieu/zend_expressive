<?php

declare(strict_types=1);

namespace App;

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
                Handler\VerifyODMConfigHandler::class => Factory\VerifyODMConfigHandlerFactory::class,
                Handler\VerifyLogConfigHandler::class => Factory\VerifyLogConfigHandlerFactory::class,
                Handler\VerifyCORSConfigHandler::class => Factory\VerifyCORSConfigHandlerFactory::class,
                Handler\VerifyRSAConfigHandler::class => Factory\VerifyRSAConfigHandlerFactory::class,
                Handler\GetPublicKeyHandler::class => Factory\GetPublicKeyHandlerFactory::class,
                Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
                Handler\ViewPageHandler::class => Handler\ViewPageHandlerFactory::class,   
                Handler\GetLogHandler::class => \Infrastructure\Factory\BaseFactory::class,   
                
                Validator\ValidatorMiddleware::class => Factory\ValidatorMiddlewareFactory::class,  
                Services\SectionServiceInterface::class => Factory\SectionService::class,           
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
