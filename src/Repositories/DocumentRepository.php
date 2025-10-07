<?php
namespace Televice\Repositories;

use DateTimeImmutable;
use Televice\Support\Database;

class DocumentRepository
{
    public function create(int $applicationId, array $document): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO documents (application_id, document_key, original_path, watermarked_path, filename, mime_type, file_size, hash, created_at) VALUES (:application_id, :document_key, :original_path, :watermarked_path, :filename, :mime_type, :file_size, :hash, :created_at)');
        $stmt->execute([
            'application_id' => $applicationId,
            'document_key' => $document['document_key'],
            'original_path' => $document['original_path'],
            'watermarked_path' => $document['watermarked_path'],
            'filename' => $document['filename'],
            'mime_type' => $document['mime_type'],
            'file_size' => $document['file_size'],
            'hash' => $document['hash'],
            'created_at' => (new DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
        ]);
    }

    public function findByApplication(int $applicationId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM documents WHERE application_id = :application_id ORDER BY created_at ASC');
        $stmt->execute(['application_id' => $applicationId]);
        return $stmt->fetchAll();
    }
}
