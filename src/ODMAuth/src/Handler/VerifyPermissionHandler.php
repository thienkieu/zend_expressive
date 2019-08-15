<?php

declare(strict_types=1);

namespace ODMAuth\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class VerifyPermissionHandler implements RequestHandlerInterface
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
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);

        $dm = $this->container->get(\Config\AppConstant::DocumentManager);
        $authorizationService = $this->container->get(\ODMAuth\Services\Interfaces\AuthorizationServiceInterface::class);
        $ok = $authorizationService->isAllow($dto->userId, $dto->action, $messages);

        return new JsonResponse([
            'welcome' => 'setup permission data success',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
            'status'  => $ok,
            'messages' => $messages,
        ]);
        
    }
}
