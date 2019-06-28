<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\DoExamResultServiceInterface;

class SynchronyTimeHandler implements RequestHandlerInterface
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
        $exExamResultService = $this->container->build(DoExamResultServiceInterface::class, [\Config\AppConstant::DTOKey => $dto]);
        $ret = $exExamResultService->synchronyTime($dto, $outRemainTime, $messages);
        $data = new \stdClass();
        $data->remainTime = $outRemainTime;

        return \Infrastructure\CommonFunction::buildResponseFormat($ret, $messages, $data);
    }
}