<?php

declare(strict_types=1);

namespace ODMAuth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class SetupPermissionHandler implements RequestHandlerInterface
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
        $dm = $this->container->get(\Config\AppConstant::DocumentManager);

        $codeFunctions = [];
        $codeFunctions[] ="questions.create";
        $codeFunctions[] ="questions.update";
        $codeFunctions[] ="questions.delete";
        $codeFunctions[] ="questions.import";
        $codeFunctions[] ="question.questions";
        $codeFunctions[] ="question.export";

        $permissionDocument = new \ODMAuth\Documents\PermissionDocument();
        $permissionDocument->setBusinessName("Question management");
        $permissionDocument->setCodeFunctions($codeFunctions);
        $dm->persist($permissionDocument);

        $permissionArray = new ArrayCollection();
        $permissionArray->add($permissionDocument);

        $user = new \ODMAuth\Documents\UserDocument();
        $user->setUsername('test_permission');
        $user->setPassword('$2y$10$DW12wQQvr4w7mQ.uSmz37OQkKcIZrRZnpXWoYue7b5v8E/pxvsAru');
        $user->setPermissionDocument($permissionArray);
        $dm->persist($user);

        $userArray = new ArrayCollection();
        $userArray->add($user);

        $groups = new \ODMAuth\Documents\GroupsDocument();
        $groups->setName('HR-Question');
        $groups->setDescription('Only working with question');
        $groups->setPermissionDocument($permissionArray);
        $groups->setUserDocument($userArray);

        $dm->persist($groups);
        $dm->flush();


        return new JsonResponse([
            'welcome' => 'setup permission data success',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        ]);
        
    }
}
