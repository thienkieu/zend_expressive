<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\ImportQuestionServiceInterface;

class ImportQuestionHandler implements RequestHandlerInterface
{    
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        $importQuestionService = $this->container->get(ImportQuestionServiceInterface::class);
        $ret = $importQuestionService->importQuestion($dto, $resultDTO, $messages);

        return new JsonResponse([
            'isSuccess' => $ret,      
            'messages'  => $messages,
            'data' => $resultDTO
        ]);
        
    }
}
