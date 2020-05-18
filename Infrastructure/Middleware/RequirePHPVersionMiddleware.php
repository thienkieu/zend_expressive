<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Infrastructure\CommonFunction;

class RequirePHPVersionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {   
        if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 70200) {
            return new JsonResponse([
                'success' => false,
                'messages' => 'Require PHP 7.2 or larger.',
                'data' => new \stdClass()
            ]);
        }

        return $handler->handle($request);
    }
}