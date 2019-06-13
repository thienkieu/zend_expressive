<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\ExamServiceInterface;

class EnterPinHandler implements RequestHandlerInterface
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
        $examService = $this->container->get(ExamServiceInterface::class);
        $ret = $examService->enterPin($dto, $resultDTO, $messages);

        return new JsonResponse([
            'isSuccess' => $ret,      
            'messages'  => $messages,
            'exam' => $resultDTO
        ]);
        
    }
}