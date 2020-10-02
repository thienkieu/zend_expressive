<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;

class VerifyAPIHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $dto = $request->getAttribute(\Config\AppConstant::DTODataFieldName);       
        $examService = $this->container->get($dto->serviceClass);
        $ok = $examService->completeExam($dto->id, $messages);
        return new JsonResponse([
            'success' => $ok,
            'messages' => $messages,
            'data' => ''
        ]);
    }
}
