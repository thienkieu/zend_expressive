<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Config\AppConstant;
use Infrastructure\CommonFunction;
use Infrastructure\Services\Interfaces\LogInterface;

class LogMiddleware implements MiddlewareInterface
{
    private $container;
    private $options = null;
    private $logger = null;
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
        $rotuer = $request->getAttribute(\Zend\Expressive\Router\RouteResult::class);
        $routerName = $rotuer->getMatchedRouteName(); 
        if ($routerName !== 'home') {
            $logObject = [
                'token' => $request->getHeader('Authorization'),
                'data' => (string) $request->getBody(),
                'param' => $request->getQueryParams(),
                'routerName' =>  $routerName
            ];
           
            $logService = $this->container->get(LogInterface::class);
            $logService->writeLog($logObject);
        }
       
        
        $response = $handler->handle($request);
        return $response;
    }
}