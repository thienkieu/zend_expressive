<?php

declare(strict_types=1);

namespace ODMAuth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;


class SetupSampleDataHandler implements RequestHandlerInterface
{
   
    /** @var Psr\Container\ContainerInterface */
    private $container;

    public function __construct(        
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {

        $client = new \ODMAuth\Document\ClientDocument();
        $client->setName('client_test');
        $client->setSecret('$2y$10$fFlZTo2Syqa./0JJ2QKV4O/Nfi9cqDMcwHBkN/WMcRLLlaxYUP2CK');
        $client->setRedirect('/redirect');
        $client->setPersonalAccessClient(1);
        $client->setPasswordClient(1);

        $client2 = new \ODMAuth\Document\ClientDocument();
        $client2->setName('client_test2');
        $client2->setSecret('$2y$10$fFlZTo2Syqa./0JJ2QKV4O/Nfi9cqDMcwHBkN/WMcRLLlaxYUP2CK');
        $client2->setRedirect('/redirect');
        $client2->setPersonalAccessClient(0);
        $client2->setPasswordClient(0);

        $user = new \ODMAuth\Document\UserDocument();
        $user->setUsername('user_test');
        $user->setPassword('$2y$10$DW12wQQvr4w7mQ.uSmz37OQkKcIZrRZnpXWoYue7b5v8E/pxvsAru');

        $scope = new \ODMAuth\Document\ScopeDocument();
        $scope->setId('test');

        $dm = $this->container->get(\Config\AppConstant::DocumentManager);

        $dm->persist($client);
        $dm->persist($client2);
        $dm->persist($user);
        $dm->persist($scope);

        $dm->flush();


        return new JsonResponse([
            'welcome' => 'setup sample auth data',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        ]);
        
    }
}
