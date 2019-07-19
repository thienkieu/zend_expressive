<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Authentication\OAuth2;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->post('/token', OAuth2\TokenEndpointHandler::class, 'api.token');
    $app->get('/publickey', App\Handler\GetPublicKeyHandler::class, 'api.publickey');

    //odm auth
    $app->get('/odmauth/setup', ODMAuth\Handler\SetupSampleDataHandler::class, 'odmauth.setup');
};
