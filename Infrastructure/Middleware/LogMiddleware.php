<?php
namespace Infrastructure\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Config\AppConstant;
use Infrastructure\CommonFunction;

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
            $appConfig = $this->container->get(\Config\AppConstant::AppConfig);
            $trackingConnectConfig = $appConfig[\Config\AppConstant::TRACKING_CONNECT];
            $writeLogURL = $trackingConnectConfig[\Config\AppConstant::WriteLogURL];
            $enableLogFile = $trackingConnectConfig[\Config\AppConstant::EnableLogFile];
            $enableLogRemote = $trackingConnectConfig[\Config\AppConstant::EnableLogRemote];
           
            $logObject = [
                'token' => $request->getHeader('Authorization'),
                'data' => (string) $request->getBody(),
                'param' => $request->getQueryParams(),
                'routerName' =>  $routerName
            ];
           
            if ($enableLogFile === true) {
                $logger = $this->container->get(\Zend\Log\Logger::class);
                $logger->info(print_r($logObject, true));
            }
            
            if ($enableLogRemote === true) {
                $this->writeLogAsyn($writeLogURL,  $logObject );
            }
        }
       
        
        $response = $handler->handle($request);
        return $response;
    }

    public function writeLogAsyn($url, $params, $type='POST')
    {
        foreach ($params as $key => &$val) {
            if (is_array($val)) $val = implode(',', $val);
            $post_params[] = $key.'='.urlencode($val);
        }
        $post_string = implode('&', $post_params);

        $parts=parse_url($url);

        $fp = fsockopen($parts['host'],
            isset($parts['port'])?$parts['port']:80,
            $errno, $errstr, 30);

        // Data goes in the path for a GET request
        if('GET' == $type) $parts['path'] .= '?'.$post_string;

        $out = "$type ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        // Data goes in the request body for a POST request
        if ('POST' == $type && isset($post_string)) $out.= $post_string;

        fwrite($fp, $out);
        fclose($fp);
    }
}