<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\PlatformServiceInterface;

class CreatePlatformHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dtoObject = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $typeService = $this->container->get(PlatformServiceInterface::class);
        $ok = $typeService->createPlatform($dtoObject, $returnDTO, $messages);

        return \Infrastructure\CommonFunction::buildResponseFormat($ok, $messages, $returnDTO);
    }
}