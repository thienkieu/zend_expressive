<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Authentication\UserInterface;
use Zend\Expressive\Authentication\AuthenticationInterface;

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
        $user = $request->getAttribute(UserInterface::class);
        $doExamResultService = $this->container->get(DoExamResultServiceInterface::class);
        $ret = $doExamResultService->getExamResult($dto, $messages, $examResultDTO);

        $authorizationService = $this->container->get(AuthenticationInterface::class);
        $user = $authorizationService->authenticate($request);
        if ($user && $user->getIdentity()) {
            return new JsonResponse([
                'isSuccess' => $ret,      
                'messages'  => $messages,
                'summary'   => $examResultDTO->getResultSummary(),
                'exam' => $examResultDTO
            ]);
        } else {
            return new JsonResponse([
                'isSuccess' => $ret,      
                'messages'  => $messages,
                'summary'   => $examResultDTO->getResultSummary()
            ]);
        }
        

    }
}
