<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use MongoDB\Client;
use Doctrine\Common\Cache\ApcuCache;

class DoctrineODMFactory
{
    public function __invoke(ContainerInterface $container) : DocumentManager
    {
        $appConfig = $container->get('config');
        $environment = $appConfig['environment'];
        $dbConfig = $appConfig['nonsqldb'];
        if (isset($appConfig['nonsqldb_'.$environment])) {
            $dbConfig = $appConfig['nonsqldb_'.$environment];
        }

        $client = new Client($dbConfig['mongodb-connection'], [], ['typeMap' => ['root' => 'array', 'document' => 'array']]);
        $connection = new Connection($client);

        $config = new Configuration();
        $config->setProxyDir($dbConfig['proxy-path']);
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir($dbConfig['hydrators-path']);
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB($dbConfig['dbname']);

        
        $config->setAutoGenerateHydratorClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
        $config->setAutoGenerateProxyClasses(\Doctrine\Common\Proxy\AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);
        //config->setMetadataCacheImpl(new ApcuCache());
        

        $config->setMetadataDriverImpl(AnnotationDriver::create($dbConfig['document-path']));
        
        

        AnnotationDriver::registerAnnotationClasses();
        
        $dm = DocumentManager::create($connection, $config);
        
        return $dm;
    }
}
