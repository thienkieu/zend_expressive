<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Interfaces\TestTemplateServiceInterface;

class DeleteTestTemplateHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $testTemplateService = $this->container->get(TestTemplateServiceInterface::class);
        $ok = $testTemplateService->deleteTestTemplate($dto->id, $messages);
        return new JsonResponse([
            'success' => $ok,
            'messages' => $messages,
            'data' => ''
        ]);
    }
}