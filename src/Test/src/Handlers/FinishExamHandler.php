<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\DoExamResultServiceInterface;

class FinishExamHandler implements RequestHandlerInterface
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
        
        $doExamResultService = $this->container->get(DoExamResultServiceInterface::class);
        $ret = $doExamResultService->finish($dto, $messages);

        return \Infrastructure\CommonFunction::buildResponseFormat($ret, $messages);        
    }
}
