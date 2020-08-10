<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Config\AppConstant;
use Infrastructure\CommonFunction;
use Infrastructure\Services\Interfaces\LogInterface;
use Zend\Expressive\Router\RouteResult;

class VerifyClientBuidMiddleware implements MiddlewareInterface
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
        $clientBuild = $request->getHeader('clientbuild');
        $appConfig = $this->container->get('config');
        $clientConfig = $appConfig['clientConfig'];
        
        $rotuer = $request->getAttribute(RouteResult::class);
        $routerName = $rotuer->getMatchedRouteName(); 
        $authenticationExcludeUrl = $appConfig[\Config\AppConstant::AuthenticationExcludeUrl];
        /*$logService = $this->container->get(\Infrastructure\Services\LogService::class);
        $logService->writeLog($clientConfig);
        
        $logService->writeLog($clientBuild !== $clientConfig['clientBuild']);
        $logService->writeLog($clientBuild);
        $logService->writeLog($clientConfig['clientBuild']);*/
        if ($routerName && !in_array($routerName, $authenticationExcludeUrl) && $clientBuild[0] !== $clientConfig['clientBuild']) {
            $translator = $this->container->get(AppConstant::Translator);
            $messages[] =  $translator->translate("Invalid application version. Please clear your browser's cache.");
            $data = new \stdClass();
            $data->errorCode = 'INVALID_BUILD';
            return CommonFunction::buildResponseFormat(false, $messages, $data);
        }

        $response = $handler->handle($request);
        return $response;
    }
}