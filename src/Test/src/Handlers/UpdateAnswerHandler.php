<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\DoExamResultServiceInterface;

class UpdateAnswerHandler implements RequestHandlerInterface
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
        
        $dto->setExamId($examOfCandidateInfo->examId);
        $dto->setCandidateId($examOfCandidateInfo->candidateId);
        echo '<pre>'.print_r($dto, true).'</pre>'; die;
        $exExamResultService = $this->container->build(DoExamResultServiceInterface::class, [\Config\AppConstant::DTOKey => $dto]);
        $ret = $exExamResultService->updateAnswer($dto, $messages);

        return \Infrastructure\CommonFunction::buildResponseFormat($ret, $messages);
    }
}
