# DoctrineMigrationsExtraBundle Architecture

## Purpose

A Sylius-Labs extension bundle for Doctrine Migrations that adds two features:
topological migration ordering (respecting inter-migration dependencies) and
container-aware migration instantiation (constructor DI for migrations).

## Directory Structure

```
src/
  Sylius_Labs_Doctrine_Migrations_Extra_Bundle.php   — bundle entry point
  DependencyInjection/
    Configuration.php                                 — defines bundle config tree
    Sylius_Labs_Doctrine_Migrations_Extra_Extension.php — loads config and services
  Comparator/
    Topological_Map.php                               — DAG-based dependency graph for migrations
    Topological_Version_Comparator.php                — sorts versions using topological order
  Factory/
    Container_Aware_Version_Factory.php               — creates migration instances from the DI container
  Resources/config/
    services.php                                      — service definitions
```

## Key Design Decisions

- **Topological ordering**: `Topological_Version_Comparator` implements
  Doctrine Migrations' `Comparator\MigrationComparator` interface so that
  migrations declaring dependencies on other migrations are always executed
  after their prerequisites, regardless of timestamp ordering.
- **`Topological_Map`** builds a directed acyclic graph (DAG) from declared
  migration `dependsOn` metadata and performs a topological sort to produce
  a stable, dependency-respecting execution order.
- **Container-aware factory**: `Container_Aware_Version_Factory` delegates
  migration instantiation to the Symfony PSR-11 container when the migration
  class is a registered service, enabling constructor injection.

## Extension Points

- Tag a migration as a service and implement constructor DI — the
  `Container_Aware_Version_Factory` handles instantiation automatically.
- Declare migration dependencies via the `dependsOn` metadata field to control
  topological ordering.

## Dependency Flow

```
Symfony Kernel
  └── SyliusLabsDoctrineMigrationsExtraBundle
        ├── Container_Aware_Version_Factory  → PSR-11 Container
        └── Topological_Version_Comparator
              └── Topological_Map  (DAG sort)
```
