<?php
namespace Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $dtoObject = isset($options[\Config\AppConstant::DTOKey]) ? $options[\Config\AppConstant::DTOKey]: null ;
        $appConfig = $container->get(\Config\AppConstant::AppConfig);
        $serviceResolveConfig = $appConfig[\Config\AppConstant::ResolveService];
        $testServiceSupport = $serviceResolveConfig[$requestedName]; 
        
        foreach ($testServiceSupport as $serviceClass) {
            $service = $container->get($serviceClass);
            if ($service->isHandler($dtoObject)) return $service;
        }
        
        return new $requestedName($container, $options);
    }
}
