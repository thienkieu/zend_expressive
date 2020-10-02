<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\TrackingConnectServiceInterface;


class VerifyAPIHandler implements RequestHandlerInterface
{    
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $messages = [];
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $trackingService = $this->container->get(TrackingConnectServiceInterface::class);
        $ok = $trackingService->getLatestDisconnect($dto->token);
        
        return new JsonResponse([
            'result' => $ok,      
        ]);
        
    }
}
