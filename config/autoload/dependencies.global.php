<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,            
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories'  => [
            'translator'                        => Infrastructure\i18n\TranslatorFactory::class,
            Infrastructure\i18n\LocaleMiddleware::class =>   Infrastructure\i18n\LocaleMiddlewareFactory::class,         
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
            'entityManager'                     => App\Factory\DoctrineORMFactory::class,
            'documentManager'                   => App\Factory\DoctrineODMFactory::class,            
            
            Infrastructure\Convertor\DTOToDocumentConvertorInterface::class => Infrastructure\Convertor\DTOToDocumentConvertorFactory::class,
            Infrastructure\Convertor\RequestToDTOConvertorInterface::class => Infrastructure\Convertor\RequestToDTOConvertorFactory::class,
            Infrastructure\Convertor\DocumentToDTOConvertorInterface::class => Infrastructure\Convertor\DocumentToDTOConvertorFactory::class,
            Infrastructure\Convertor\RequestToDTOMiddleware::class => Infrastructure\Factory\BaseFactory::class,
            Infrastructure\Validator\ValidatorRequestInterface::class => Infrastructure\Validator\ValidatorRequestFactory::class,
            Infrastructure\Middleware\UploadFileMiddleware::class => Infrastructure\Factory\BaseFactory::class,
            Infrastructure\Middleware\LogMiddleware::class => Infrastructure\Factory\BaseFactory::class,

            Infrastructure\DataParser\DataParserInterface::class => Infrastructure\Factory\ServiceFactory::class,
            Infrastructure\DataParser\ExcelParserService::class => Infrastructure\Factory\BaseFactory::class,
            Infrastructure\DataParser\WordParserService::class => Infrastructure\Factory\BaseFactory::class,
            Infrastructure\DataParser\HtmlFormatAdapter::class => Infrastructure\Factory\BaseFactory::class,
            

            Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware::class => Infrastructure\Factory\BodyParamsMiddlewareFactory::class,
            
            Tuupola\Middleware\CorsMiddleware::class => Infrastructure\Factory\CorsMiddlewareFactory::class,
        ]
    ]
];
