<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\ExamServiceInterface;

class CompleteExamHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $examService = $this->container->get(ExamServiceInterface::class);
        $ok = $examService->completeExam($dto->id, $messages);
        return new JsonResponse([
            'success' => $ok,
            'messages' => $messages,
            'data' => ''
        ]);
    }
}
