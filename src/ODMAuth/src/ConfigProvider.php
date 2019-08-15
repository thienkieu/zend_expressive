<?php

declare(strict_types=1);

namespace ODMAuth;

use League\OAuth2\Server\Grant;
use Infrastructure;
use Zend;
use League;

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
            'authentication' => [
                'private_key'    => __DIR__ . '/../data/private.key',
                'public_key'     => __DIR__ . '/../data/public.key',
                'encryption_key' => require __DIR__ . '/../data/encryption.key',
                'access_token_expire'  => 'P1D',
                'refresh_token_expire' => 'P1M',
                'auth_code_expire'     => 'PT10M',
                'pdo' => [
                    'dsn'      => 'mysql:host=localhost;dbname=onlinetest',
                    'username' => 'root',
                    'password' => ''
                ],
                
                'grants' => [
                    Grant\ClientCredentialsGrant::class => Grant\ClientCredentialsGrant::class,
                    Grant\PasswordGrant::class          => Grant\PasswordGrant::class,
                    Grant\AuthCodeGrant::class          => Grant\AuthCodeGrant::class,
                    Grant\ImplicitGrant::class          => Grant\ImplicitGrant::class,
                    Grant\RefreshTokenGrant::class      => Grant\RefreshTokenGrant::class,
                    \ODMAuth\Grant\SSOGrant::class      => \ODMAuth\Grant\SSOGrant::class,            
                ],
        
            ]        
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [                
            ],
            'factories'  => [
                Handler\SetupSampleDataHandler::class => \Infrastructure\Factory\BaseFactory::class,

                \ODMAuth\Middleware\AuthenticationMiddleware::class => \ODMAuth\Factory\AuthenticationMiddlewareFactory::class,
                Zend\Expressive\Authentication\OAuth2\OAuth2Adapter::class => Zend\Expressive\Authentication\OAuth2\OAuth2AdapterFactory::class,
                League\OAuth2\Server\ResourceServer::class => Zend\Expressive\Authentication\OAuth2\ResourceServerFactory::class,  
                Zend\Expressive\Authentication\OAuth2\TokenEndpointHandler::class => Zend\Expressive\Authentication\OAuth2\TokenEndpointHandlerFactory::class,
                League\OAuth2\Server\AuthorizationServer::class => Zend\Expressive\Authentication\OAuth2\AuthorizationServerFactory::class,
                
                League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface::class => \ODMAuth\Repositories\AccessTokenRepositoryFactory::class,
                League\OAuth2\Server\Repositories\ClientRepositoryInterface::class => \ODMAuth\Repositories\ClientRepositoryFactory::class,
                League\OAuth2\Server\Repositories\ScopeRepositoryInterface::class => \ODMAuth\Repositories\ScopeRepositoryFactory::class,
                League\OAuth2\Server\Repositories\UserRepositoryInterface::class => \ODMAuth\Repositories\UserRepositoryFactory::class,
                League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface::class => \ODMAuth\Repositories\RefreshTokenRepositoryFactory::class,
                League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::class => \ODMAuth\Repositories\AuthCodeRepositoryFactory::class,
                
                \ODMAuth\Handler\SetupPermissionHandler::class => \Infrastructure\Factory\BaseFactory::class,
                \ODMAuth\Handler\VerifyPermissionHandler::class => \Infrastructure\Factory\BaseFactory::class,

                League\OAuth2\Server\Grant\ClientCredentialsGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\ClientCredentialsGrantFactory::class,
                League\OAuth2\Server\Grant\PasswordGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\PasswordGrantFactory::class,
                League\OAuth2\Server\Grant\RefreshTokenGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\RefreshTokenGrantFactory::class,
                League\OAuth2\Server\Grant\AuthCodeGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\AuthCodeGrantFactory::class,
                League\OAuth2\Server\Grant\ImplicitGrant::class => Zend\Expressive\Authentication\OAuth2\Grant\ImplicitGrantFactory::class,
                \ODMAuth\Grant\SSOGrant::class                  => \ODMAuth\Grant\SSOGrantFactory::class, 

                Services\AuthorizationService::class => \Infrastructure\Factory\BaseFactory::class,


            ],
            'aliases' => [
                Zend\Expressive\Authentication\AuthenticationInterface::class => Zend\Expressive\Authentication\OAuth2\OAuth2Adapter::class,
                Services\Interfaces\AuthorizationServiceInterface::class => Services\AuthorizationService::class,
            ],
        ];
    }
}
