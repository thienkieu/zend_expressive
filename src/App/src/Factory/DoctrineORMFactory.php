<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineORMFactory
{
    public function __invoke(ContainerInterface $container) : EntityManager
    {
        $config = $container->get('config');
        $entityConfig = Setup::createAnnotationMetadataConfiguration($config['entity-path'], false);
        $entityManager = EntityManager::create($config['db'], $entityConfig);
        return $entityManager;
    }
}
