<?php

declare (strict_types=1);
namespace Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Comparator;

use MJS\Top_Sort\Implementations\Array_Sort;
final class Topological_Map
{
    /**
     * @psalm-var array<string, int>
     */
    private array $dependencies;
    /**
     * @psalm-param array<string, list<string>> $packages
     */
    public function __construct(
        /**
         * @psalm-var array<string, list<string>>
         *
         * @var array[]
         */
        private array $packages
    )
    {
        $this->dependencies = $this->build_dependencies($this->packages);
    }
    public function get_priority(string $package): int
    {
        if (!array_key_exists($package, $this->dependencies)) {
            $this->packages[$package] = [];
            $this->dependencies = $this->build_dependencies($this->packages);
        }
        return $this->dependencies[$package];
    }
    /**
     * @psalm-param array<string, list<string>> $packages
     *
     * @psalm-return array<string, int>
     */
    private function build_dependencies(array $packages): array
    {
        $sorter = new Array_Sort();
        foreach ($packages as $subject => $dependencies) {
            $sorter->add($subject, $dependencies);
        }
        /** @psalm-var array<int, string> $sorted */
        $sorted = $sorter->sort();
        return array_flip($sorted);
    }
}