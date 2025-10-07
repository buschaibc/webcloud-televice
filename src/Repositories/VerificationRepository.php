<?php
namespace Televice\Repositories;

use DateTimeImmutable;
use PDO;
use Televice\Support\Database;

class VerificationRepository
{
    public function createDraft(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO applications (reference, email, phone, applicant_type, status, payload, created_at, updated_at) VALUES (:reference, :email, :phone, :applicant_type, :status, :payload, :created_at, :updated_at)');
        $now = (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $stmt->execute([
            'reference' => $data['reference'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'applicant_type' => $data['applicant_type'],
            'status' => $data['status'] ?? 'draft',
            'payload' => json_encode($data['payload'] ?? []),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        return (int) $pdo->lastInsertId();
    }

    public function updatePayload(int $id, array $payload): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('UPDATE applications SET payload = :payload, updated_at = :updated_at WHERE id = :id');
        $stmt->execute([
            'payload' => json_encode($payload),
            'updated_at' => (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
            'id' => $id,
        ]);
    }

    public function findByEmail(string $email): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applications WHERE email = :email AND deleted_at IS NULL ORDER BY created_at DESC');
        $stmt->execute(['email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPhoneUsage(string $phone, ?string $excludeEmail = null): int
    {
        $query = 'SELECT COUNT(DISTINCT email) FROM applications WHERE phone = :phone AND deleted_at IS NULL';
        $params = ['phone' => $phone];
        if ($excludeEmail) {
            $query .= ' AND email <> :email';
            $params['email'] = $excludeEmail;
        }
        $stmt = Database::connection()->prepare($query);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function findOpenCaseByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applications WHERE email = :email AND status IN ("draft", "pending", "review", "need_more_docs") AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function markSubmitted(int $id, array $payload): void
    {
        $stmt = Database::connection()->prepare('UPDATE applications SET status = "pending", submitted_at = :submitted_at, payload = :payload, updated_at = :updated_at WHERE id = :id');
        $now = (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $stmt->execute([
            'submitted_at' => $now,
            'payload' => json_encode($payload),
            'updated_at' => $now,
            'id' => $id,
        ]);
    }

    public function findByContact(string $email, string $phone): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM applications WHERE email = :email AND phone = :phone AND deleted_at IS NULL ORDER BY created_at DESC');
        $stmt->execute(['email' => $email, 'phone' => $phone]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
