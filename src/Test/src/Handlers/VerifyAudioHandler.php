<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Container\ContainerInterface;

use Test\Services\TrackingConnectServiceInterface;
use Test\Services\DoExamResultServiceInterface;


class VerifyAudioHandler implements RequestHandlerInterface
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
        $ok = $doExamResultService->getListAudioOfExam($dto, $messages, $outDTO);
        return \Infrastructure\CommonFunction::buildResponseFormat($ok, $messages, $outDTO);
    }
}
