<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\DoExamResultServiceInterface;

class ViewExamResultHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $doExamResultService = $this->container->get(DoExamResultServiceInterface::class);
        $ret = $doExamResultService->getExamResult($dto, $messages, $examResultDTO);

        
        return new JsonResponse([
            'isSuccess' => $ret,      
            'messages'  => $messages,
            'exam' => $examResultDTO
        ]);

    }
}
