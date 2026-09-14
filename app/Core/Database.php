<?php

declare(strict_types=1);

namespace App\Core;

use mysqli;
use mysqli_stmt;
use Throwable;

/**
 * Thin mysqli wrapper. Every query goes through a prepared statement; values are
 * never interpolated into SQL.
 */
final class Database
{
    private ?mysqli $connection = null;

    public function __construct(private readonly array $config)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function fetchAll(string $sql, array $params = []): array
    {
        $statement = $this->run($sql, $params);
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        return $rows;
    }

    /** @return array<string, mixed>|null */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $statement = $this->run($sql, $params);
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();

        return $row ?: null;
    }

    /** @return int Number of affected rows. */
    public function execute(string $sql, array $params = []): int
    {
        $statement = $this->run($sql, $params);
        $affected = (int) $statement->affected_rows;
        $statement->close();

        return $affected;
    }

    /** @return int The auto-increment id of the inserted row. */
    public function insert(string $sql, array $params = []): int
    {
        $statement = $this->run($sql, $params);
        $id = (int) $statement->insert_id;
        $statement->close();

        return $id;
    }

    /**
     * Run the callback inside a transaction; roll back on any exception.
     *
     * @template T
     * @param callable(Database): T $callback
     * @return T
     */
    public function transaction(callable $callback): mixed
    {
        $connection = $this->connection();
        $connection->begin_transaction();

        try {
            $result = $callback($this);
            $connection->commit();

            return $result;
        } catch (Throwable $exception) {
            $connection->rollback();

            throw $exception;
        }
    }

    private function run(string $sql, array $params): mysqli_stmt
    {
        $statement = $this->connection()->prepare($sql);
        $statement->execute($params === [] ? null : array_values($params));

        return $statement;
    }

    private function connection(): mysqli
    {
        if ($this->connection === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $connection = new mysqli(
                (string) $this->config['host'],
                (string) $this->config['user'],
                (string) $this->config['password'],
                (string) $this->config['name'],
                (int) $this->config['port'],
            );
            $connection->set_charset((string) $this->config['charset']);

            $this->connection = $connection;
        }

        return $this->connection;
    }
}
