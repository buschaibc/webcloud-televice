<?php
namespace Televice\Support;

class TrafficLogger
{
    public static function log(string $path, string $method, ?string $email = null): void
    {
        try {
            $stmt = Database::connection()->prepare('INSERT INTO traffic_logs (email, ip_address, user_agent, path, method, created_at) VALUES (:email, :ip, :agent, :path, :method, UTC_TIMESTAMP())');
            $stmt->execute([
                'email' => $email,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                'agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                'path' => $path,
                'method' => $method,
            ]);
        } catch (\Throwable $e) {
            // fail silently to avoid breaking user flow
        }
    }
}
