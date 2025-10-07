<?php
namespace Televice\Repositories;

use DateInterval;
use DateTimeImmutable;
use Televice\Support\Database;

class RateLimitRepository
{
    public function hit(string $identifier, int $windowSeconds): int
    {
        $pdo = Database::connection();
        $expiresAt = (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->sub(new DateInterval('PT' . $windowSeconds . 'S'))->format('Y-m-d H:i:s');

        $pdo->prepare('DELETE FROM rate_limit_hits WHERE identifier = :identifier AND created_at < :expires')->execute([
            'identifier' => $identifier,
            'expires' => $expiresAt,
        ]);

        $pdo->prepare('INSERT INTO rate_limit_hits (identifier, created_at) VALUES (:identifier, UTC_TIMESTAMP())')->execute([
            'identifier' => $identifier,
        ]);

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM rate_limit_hits WHERE identifier = :identifier');
        $stmt->execute(['identifier' => $identifier]);
        return (int) $stmt->fetchColumn();
    }
}
