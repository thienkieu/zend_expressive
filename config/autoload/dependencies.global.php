<?php

declare(strict_types=1);

use Zend\Expressive\Authentication;
use League\OAuth2\Server\Grant;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
            Authentication\AuthenticationInterface::class => Authentication\OAuth2\OAuth2Adapter::class,
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
            'logger'                               => App\Factory\LogFactory::class,
            
            Infrastructure\Convertor\DTOToDocumentConvertorInterface::class => Infrastructure\Convertor\DTOToDocumentConvertorFactory::class,
            Infrastructure\Convertor\RequestToDTOConvertorInterface::class => Infrastructure\Convertor\RequestToDTOConvertorFactory::class,
            Infrastructure\Convertor\RequestToDTOMiddleware::class => Infrastructure\Factory\BaseFactory::class,
            Infrastructure\Validator\ValidatorRequestInterface::class => Infrastructure\Validator\ValidatorRequestFactory::class,
            
            Authentication\AuthenticationMiddleware::class => Authentication\AuthenticationMiddlewareFactory::class,
            Authentication\OAuth2\OAuth2Adapter::class => Authentication\OAuth2\OAuth2AdapterFactory::class,
            League\OAuth2\Server\ResourceServer::class => Authentication\OAuth2\ResourceServerFactory::class,  
            League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::class => Authentication\OAuth2\Repository\Pdo\AccessTokenRepositoryFactory::class,
            Authentication\OAuth2\Repository\Pdo\PdoService::class => Authentication\OAuth2\Repository\Pdo\PdoServiceFactory::class,
            Zend\Expressive\Authentication\OAuth2\TokenEndpointHandler::class => Zend\Expressive\Authentication\OAuth2\TokenEndpointHandlerFactory::class,
            League\OAuth2\Server\AuthorizationServer::class => Authentication\OAuth2\AuthorizationServerFactory::class,
            League\OAuth2\Server\Repositories\ClientRepositoryInterface::class => Zend\Expressive\Authentication\OAuth2\Repository\Pdo\ClientRepositoryFactory::class,
            League\OAuth2\Server\Repositories\ScopeRepositoryInterface::class => Zend\Expressive\Authentication\OAuth2\Repository\Pdo\ScopeRepositoryFactory::class,
            League\OAuth2\Server\Repositories\UserRepositoryInterface::class => Zend\Expressive\Authentication\OAuth2\Repository\Pdo\UserRepositoryFactory::class,
            League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::class => Zend\Expressive\Authentication\OAuth2\Repository\Pdo\RefreshTokenRepositoryFactory::class,
            League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::class => Zend\Expressive\Authentication\OAuth2\Repository\Pdo\AuthCodeRepositoryFactory::class,
            
            League\OAuth2\Server\Grant\ClientCredentialsGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\ClientCredentialsGrantFactory::class,
            League\OAuth2\Server\Grant\PasswordGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\PasswordGrantFactory::class,
            League\OAuth2\Server\Grant\RefreshTokenGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\RefreshTokenGrantFactory::class,
            League\OAuth2\Server\Grant\AuthCodeGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\AuthCodeGrantFactory::class,
            League\OAuth2\Server\Grant\ImplicitGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\ImplicitGrantFactory::class,
            

        ],

        // Set value to null to disable a grant
        'grants' => [
            Grant\ClientCredentialsGrant::class => Grant\ClientCredentialsGrant::class,
            Grant\PasswordGrant::class          => Grant\PasswordGrant::class,
            Grant\AuthCodeGrant::class          => Grant\AuthCodeGrant::class,
            Grant\ImplicitGrant::class          => Grant\ImplicitGrant::class,
            Grant\RefreshTokenGrant::class      => Grant\RefreshTokenGrant::class
        ],
    ]
];
