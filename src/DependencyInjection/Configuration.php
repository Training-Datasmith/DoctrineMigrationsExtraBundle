<?php

declare (strict_types=1);
namespace Sylius_Labs\Doctrine_Migrations_Extra_Bundle\Dependency_Injection;

use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Config\Definition\Builder\Tree_Builder;
use Symfony\Component\Config\Definition\Configuration_Interface;
final class Configuration implements Configuration_Interface
{
    public function get_config_tree_builder(): Tree_Builder
    {
        $tree_builder = new Tree_Builder('sylius_labs_doctrine_migrations_extra');
        /** @var ArrayNodeDefinition $rootNode */
        $root_node = $tree_builder->get_root_node();
        $root_node->children()->array_node('migrations')->use_attribute_as_key('subject')->array_prototype()->perform_no_deep_merging()->scalar_prototype();
        return $tree_builder;
    }
}