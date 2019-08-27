<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\DoExamResultServiceInterface;

class GetLatestJoinedHandler implements RequestHandlerInterface
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

        $ok = $examServiceResult->getLatestExamJoined($exams, $dto);
        return new JsonResponse([
            'isSuccess' => $ok,
            'exams' => $exams,
        ]);
    }
}