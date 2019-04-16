<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use MongoDB\Client;
use Zend\Log\Writer\Stream;
use Zend\Log\LoggerInterface;
use Zend\Log\Logger;

class LogFactory
{
    public function __invoke(ContainerInterface $container) : LoggerInterface
    {
        $appConfig = $container->get('config');
        if ($appConfig['log']['enable']) {
            $logger = new Logger;
            $logger->addWriter($appConfig['log']['stream']);
            return $logger;
        } else {
            return new \App\EmptyLogger();
        }
        
    }
}
