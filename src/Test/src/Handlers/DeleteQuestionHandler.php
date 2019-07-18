<?php

declare(strict_types=1);

namespace Test\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

use Test\Services\Question\QuestionServiceInterface;

class DeleteQuestionHandler implements RequestHandlerInterface
{
    /** @var Psr\Container\ContainerInterface */
    private $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;        
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    { 
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);
        
        $questionService = $this->container->get(QuestionServiceInterface::class);
        $ok = $questionService->deleteQuestion($dto->id, $messages);
   
        return \Infrastructure\CommonFunction::buildResponseFormat($ok, $messages);
    }
}
