<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Config\AppConstant;
use Infrastructure\CommonFunction;
use phpseclib\Crypt\RSA;
use Zend\Expressive\Authentication\OAuth2;

class AddSecurityCodeMiddleware implements MiddlewareInterface
{
    private $container;
    private $options = null;
    /**
     * Class constructor.
     */
    public function __construct($container, $options = null)
    {
        $this->container = $container;
        $this->options = $options;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $response = $handler->handle($request);
        $tokenHandler = $this->container->get(OAuth2\TokenEndpointHandler::class);
        $respone = $tokenHandler->handle($request);
        $response = $response->withAddedHeader('securityCode', 'thien');
        return $response;

    }
}