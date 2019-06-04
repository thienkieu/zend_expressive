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
        $dbConfig = $config['sqldb'];

        $entityConfig = Setup::createAnnotationMetadataConfiguration($dbConfig['entity-path'], false);
        $entityManager = EntityManager::create($dbConfig['db'], $entityConfig);
        return $entityManager;
    }
}
