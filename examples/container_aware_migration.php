<?php

declare(strict_types=1);

/**
 * Example: A Doctrine Migration that uses constructor injection via the DI container.
 *
 * Register this class as a Symfony service tagged with `doctrine_migrations.migration`.
 * The Container_Aware_Version_Factory will instantiate it from the container,
 * allowing constructor injection of any registered service.
 */

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;

/**
 * Migration that injects a logger via the DI container.
 *
 * services.yaml:
 *   App\Migration\Version20260101000000:
 *     tags: [{ name: doctrine_migrations.migration }]
 */
final class Version20260101000000 extends AbstractMigration
{
    public function __construct(
        \Doctrine\DBAL\Connection $connection,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($connection, new \Psr\Log\NullLogger());
    }

    public function getDescription(): string
    {
        return 'Add the audit_log table';
    }

    public function up(Schema $schema): void
    {
        $this->logger->info('Applying migration: create audit_log table');

        $this->addSql(<<<'SQL'
            CREATE TABLE audit_log (
                id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id     INT UNSIGNED NOT NULL,
                action      VARCHAR(255) NOT NULL,
                payload     JSON,
                created_at  DATETIME NOT NULL
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->logger->info('Reverting migration: drop audit_log table');
        $this->addSql('DROP TABLE audit_log');
    }
}
