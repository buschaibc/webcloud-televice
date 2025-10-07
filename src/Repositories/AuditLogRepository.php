<?php
namespace Televice\Repositories;

use DateTimeImmutable;
use Televice\Support\Database;

class AuditLogRepository
{
    public function log(string $actorType, ?int $actorId, string $action, array $metadata = []): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO audit_logs (actor_type, actor_id, action, metadata, created_at) VALUES (:actor_type, :actor_id, :action, :metadata, :created_at)');
        $stmt->execute([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'action' => $action,
            'metadata' => json_encode($metadata),
            'created_at' => (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
        ]);
    }
}
