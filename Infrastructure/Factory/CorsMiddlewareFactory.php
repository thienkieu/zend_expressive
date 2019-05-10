<?php
namespace Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CorsMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        
        $appConfig = $container->get(\Config\AppConstant::AppConfig); 
        $corsConfig = $appConfig[\Config\AppConstant::CORSConfig];
        $options = [
            \Config\AppConstant::AllowHeader => $corsConfig[\Config\AppConstant::AllowHeader],
        ];

        return new \Tuupola\Middleware\CorsMiddleware($options);
    }
}
