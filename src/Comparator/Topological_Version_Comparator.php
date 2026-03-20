<?php

declare (strict_types=1);
namespace Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Comparator;

use Doctrine\Migrations\Version\Alphabetical_Comparator;
use Doctrine\Migrations\Version\Comparator;
use Doctrine\Migrations\Version\Version;
final class Topological_Version_Comparator implements Comparator
{
    /** @var Comparator */
    private $default_sorter;
    private readonly \Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Comparator\Topological_Map $map;
    /**
     * @psalm-param array<string, list<string>> $packages
     */
    public function __construct(array $packages)
    {
        $this->default_sorter = new Alphabetical_Comparator();
        $this->map = new Topological_Map($packages);
    }
    public function compare(Version $a, Version $b): int
    {
        $prefix_a = $this->get_namespace_prefix($a);
        $prefix_b = $this->get_namespace_prefix($b);
        return $this->map->get_priority($prefix_a) <=> $this->map->get_priority($prefix_b) ?: $this->default_sorter->compare($a, $b);
    }
    private function get_namespace_prefix(Version $version): string
    {
        $version = (string) $version;
        return substr($version, 0, strrpos($version, '\\') ?: 0);
    }
}