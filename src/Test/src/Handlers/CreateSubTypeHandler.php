<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\TypeServiceInterface;

class CreateSubTypeHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dtoObject = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $sourceService = $this->container->get(TypeServiceInterface::class);
        $ok = $sourceService->createSubType($dtoObject, $returnDTO, $messages);

        return new JsonResponse([
            'data' => $returnDTO,
            'isSuccess' => $ok,    
            'messages' => $messages,   
        ]);
    }
}
