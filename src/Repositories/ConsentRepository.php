<?php
namespace Televice\Repositories;

use DateTimeImmutable;
use Televice\Support\Database;

class ConsentRepository
{
    public function record(array $data): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO consent_logs (email, consent_version, ip_address, user_agent, hash, created_at) VALUES (:email, :consent_version, :ip_address, :user_agent, :hash, :created_at)');
        $stmt->execute([
            'email' => $data['email'],
            'consent_version' => $data['consent_version'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
            'hash' => $data['hash'],
            'created_at' => (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
        ]);
    }
}
