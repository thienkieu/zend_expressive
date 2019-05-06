<?php
namespace Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zend\Expressive\Helper\BodyParams\FormUrlEncodedStrategy;

class BodyParamsMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $bodyParams = new BodyParamsMiddleware();
        $bodyParams->clearStrategies();
        
        $bodyParams->addStrategy(new FormUrlEncodedStrategy());
        $bodyParams->addStrategy(new \Infrastructure\BodyParams\JsonStrategy());
        $bodyParams->addStrategy(new \Infrastructure\BodyParams\MultipartJsonStrategy());
        
        return $bodyParams;
    }
}
