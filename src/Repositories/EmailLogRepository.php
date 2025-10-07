<?php
namespace Televice\Repositories;

use DateTimeImmutable;
use Televice\Support\Database;

class EmailLogRepository
{
    public function log(array $data): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO email_logs (application_id, email, event, status, payload, created_at) VALUES (:application_id, :email, :event, :status, :payload, :created_at)');
        $stmt->execute([
            'application_id' => $data['application_id'] ?? null,
            'email' => $data['email'] ?? null,
            'event' => $data['event'],
            'status' => $data['status'],
            'payload' => json_encode($data['payload'] ?? []),
            'created_at' => (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
        ]);
    }
}
