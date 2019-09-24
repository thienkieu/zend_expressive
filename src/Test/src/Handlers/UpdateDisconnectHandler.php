<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\DoExamResultListeningServiceInterface;

class UpdateDisconnectHandler implements RequestHandlerInterface
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
        $doExamAuthorizationService = $this->container->get(\ODMAuth\Services\Interfaces\DoExamAuthorizationServiceInterface::class);
        $examOfCandidateInfo = $doExamAuthorizationService->getCandidateInfo();
        
        $dto->examId = $examOfCandidateInfo->examId;
        $dto->candidateId = $examOfCandidateInfo->candidateId;
        
        $examResultService = $this->container->get(DoExamResultListeningServiceInterface::class);
        $ret = $examResultService->updateDisconnect($dto, $messages);

        return new JsonResponse([
            'isSuccess' => $ret,      
            'messages'  => $messages,
            'pin' => $ret
        ]);
        
    }
}
