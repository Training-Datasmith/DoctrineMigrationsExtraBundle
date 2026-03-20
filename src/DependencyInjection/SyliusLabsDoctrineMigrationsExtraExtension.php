<?php

declare (strict_types=1);
namespace Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Dependency_Injection;

use Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Comparator\Topological_Version_Comparator;
use Symfony\Component\Config\Definition\Configuration_Interface;
use Symfony\Component\Config\File_Locator;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Extension\Extension;
use Symfony\Component\Dependency_Injection\Loader\Php_File_Loader;
final class Sylius_Labs_Doctrine_Migrations_Extra_Extension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     *
     * @throws \Exception
     */
    public function load(array $configs, Container_Builder $container): void
    {
        $config = $this->process_configuration($this->get_configuration([], $container), $configs);
        $loader = new Php_File_Loader($container, new File_Locator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
        $container->get_definition(Topological_Version_Comparator::class)->set_argument(0, $config['migrations']);
    }
    /**
     * @param array<string, mixed> $config
     */
    public function get_configuration(array $config, Container_Builder $container): Configuration_Interface
    {
        return new Configuration();
    }
}