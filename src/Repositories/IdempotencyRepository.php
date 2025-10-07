<?php
namespace Televice\Repositories;

use DateInterval;
use DateTimeImmutable;
use Televice\Support\Database;

class IdempotencyRepository
{
    public function exists(string $key): bool
    {
        $stmt = Database::connection()->prepare('SELECT id FROM idempotency_keys WHERE idempotency_key = :key AND expires_at > UTC_TIMESTAMP() LIMIT 1');
        $stmt->execute(['key' => $key]);
        return (bool) $stmt->fetchColumn();
    }

    public function store(string $key, string $identifier, int $ttlSeconds): void
    {
        $expires = (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->add(new DateInterval('PT' . $ttlSeconds . 'S'))->format('Y-m-d H:i:s');
        $stmt = Database::connection()->prepare('INSERT INTO idempotency_keys (idempotency_key, identifier, expires_at, created_at) VALUES (:key, :identifier, :expires_at, UTC_TIMESTAMP())');
        $stmt->execute([
            'key' => $key,
            'identifier' => $identifier,
            'expires_at' => $expires,
        ]);
    }
}
