<?php

declare (strict_types=1);
namespace Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Factory;

use Doctrine\Migrations\Abstract_Migration;
use Doctrine\Migrations\Version\Migration_Factory;
use Symfony\Component\Dependency_Injection\Container_Aware_Interface;
use Symfony\Component\Dependency_Injection\Container_Interface;
final class Container_Aware_Version_Factory implements Migration_Factory
{
    /** @var MigrationFactory */
    private $migration_factory;
    /** @var ContainerInterface */
    private $container;
    public function __construct(Migration_Factory $migration_factory, Container_Interface $container)
    {
        $this->migration_factory = $migration_factory;
        $this->container = $container;
    }
    public function create_version(string $migration_class_name): Abstract_Migration
    {
        $instance = $this->migration_factory->create_version($migration_class_name);
        if (interface_exists(Container_Aware_Interface::class) && $instance instanceof Container_Aware_Interface) {
            $instance->set_container($this->container);
        }
        return $instance;
    }
}