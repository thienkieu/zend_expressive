<?php

declare(strict_types=1);

use Zend\Expressive\Authentication;
use League\OAuth2\Server\Grant;

$dir = realpath('src');

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
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
            'entityManager'                     => App\Factory\DoctrineORMFactory::class,
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
    ],

    'db' => [
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => '',
        'dbname'   => 'onlinetest',
    ],
    'entity-path' => [
        $dir.'/App/src/Entity'
    ],
    'authentication' => [
        'private_key'    => __DIR__ . '/../../data/oauth/private.key',
        'public_key'     => __DIR__ . '/../../data/oauth/public.key',
        'encryption_key' => require __DIR__ . '/../../data/oauth/encryption.key',
        'access_token_expire'  => 'P1D',
        'refresh_token_expire' => 'P1M',
        'auth_code_expire'     => 'PT10M',
        'pdo' => [
            'dsn'      => 'mysql:host=localhost;dbname=onlinetest',
            'username' => 'root',
            'password' => ''
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
