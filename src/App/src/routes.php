<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', [App\Handler\HomePageHandler::class], 'home');
    
    // Verify config
    $app->get('/verify-odm', Test\Handlers\VerifyODMConfigHandler::class, 'verifyODM');
    $app->get('/verify-log', App\Handler\VerifyLogConfigHandler::class, 'verifyLOG');
    $app->post('/verify-rsa', App\Handler\VerifyRSAConfigHandler::class, 'verifyRSA');
    $app->post('/verify-cors', App\Handler\VerifyCORSConfigHandler::class, 'verifyCORS');
    
    $app->get('/viewLog', App\Handler\GetLogHandler::class, 'log');
    $app->post('/clientWriteLog', App\Handler\ClientLogHandler::class, 'clientWriteLog');
};
