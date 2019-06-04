<?php
namespace Test\Factories\Services;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $dtoObject = $options[\Config\AppConstant::DTOKey];
        if (!$dtoObject) {
            return null;   
        }
        
        $appConfig = $container->get(\Config\AppConstant::AppConfig);
        $testServiceSupport = $appConfig[\Config\AppConstant::TestServiceTypeKey];
        
        foreach ($testServiceSupport as $serviceClass) {
           $service = $this->container->get($serviceClass);
           if ($service->isHandler($dtoObject)) return $service;
        }
        
        return new $requestedName($container, $options);
    }
}
