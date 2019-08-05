<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\DoExamResultServiceInterface;

class GetExamJoinedHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $examServiceResult = $this->container->get(DoExamResultServiceInterface::class);
        $ok = $examServiceResult->getExamJoined($exams, $dto->type, $dto->objectId);
        return new JsonResponse([
            'success' => $ok,
            'exams' => $exams,
        ]);
    }
}
