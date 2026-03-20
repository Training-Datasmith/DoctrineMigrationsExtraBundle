<?php

declare (strict_types=1);
use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Version\Dbal_Migration_Factory;
use Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Comparator\Topological_Version_Comparator;
use Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Factory\Container_Aware_Version_Factory;
use Symfony\Component\Dependency_Injection\Loader\Configurator\Container_Configurator;
use function Symfony\Component\Dependency_Injection\Loader\Configurator\inline_service;
use function Symfony\Component\Dependency_Injection\Loader\Configurator\service;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->set(Container_Aware_Version_Factory::class)->args([inline_service(Dbal_Migration_Factory::class)->args([inline_service(Connection::class)->factory([service('doctrine.orm.entity_manager'), 'getConnection']), service('logger')]), service('service_container')]);
    $services->set(Topological_Version_Comparator::class)->args([[]]);
};