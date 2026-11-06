<?php

declare(strict_types=1);

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;

final class EntityManagerFactory
{
    public function __invoke(ContainerInterface $container): EntityManagerInterface
    {
        $config = $container->get('config')['doctrine'];

        // "read #[ORM\...] attributes on classes found in these paths"
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            paths: $config['entity_paths'],
            isDevMode: $config['dev_mode'],
        );

        $connection = DriverManager::getConnection($config['connection'], $ormConfig);

        return new EntityManager($connection, $ormConfig);
    }
}
