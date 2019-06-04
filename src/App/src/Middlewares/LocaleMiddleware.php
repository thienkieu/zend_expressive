<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Config\AppConstant;

class UploadMiddleware implements MiddlewareInterface
{
    private $container;

    /**
     * Class constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $config = $this->container->get('config');
        $uploadConfig = $config[AppConstant::UploadConfigName];
        return $handler->handle($request->withUri(
            $uri->withPath($path ?: '/')
        ));
    }
}