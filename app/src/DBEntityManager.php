<?php

declare(strict_types=1);

namespace App;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use PHPUnit\Framework\MockObject\MockObject;

class DBEntityManager
{
    private static null|EntityManager|MockObject $instance = null;

    public static function setInstance(null|MockObject $mock): void
    {
        self::$instance = $mock;
    }

    /**
     * @throws Exception
     */
    public static function getInstance(): EntityManager|MockObject
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/Entities'],
            isDevMode: true,
        );

        $conn = DriverManager::getConnection([
            'driver' => 'pdo_pgsql',
            'url' => "postgresql://root:toor@book-net-postgresql:5432/storage?charset=utf8",
        ], $config);

        return self::$instance = new EntityManager($conn, $config);
    }
}
