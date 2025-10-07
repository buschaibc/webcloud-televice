<?php
namespace Televice\Services;

use Televice\Repositories\IdempotencyRepository;
use Televice\Repositories\RateLimitRepository;
use Televice\Support\Container;

class SecurityService
{
    public function __construct(
        private IdempotencyRepository $idempotency,
        private RateLimitRepository $rateLimit
    ) {
    }

    public function assertIdempotency(?string $key, string $identifier): void
    {
        if (!$key) {
            throw new \RuntimeException('จำเป็นต้องมี Idempotency-Key');
        }
        $config = Container::get('config');
        $ttl = $config['security']['idempotency_window'];
        if ($this->idempotency->exists($key)) {
            throw new \RuntimeException('คำขอนี้ถูกส่งแล้ว');
        }
        $this->idempotency->store($key, $identifier, $ttl);
    }

    public function assertRateLimit(string $identifier): void
    {
        $config = Container::get('config');
        $count = $this->rateLimit->hit($identifier, $config['security']['rate_limit']['per_minutes'] * 60);
        if ($count > $config['security']['rate_limit']['requests']) {
            throw new \RuntimeException('ส่งคำขอมากเกินไป โปรดลองใหม่ภายหลัง');
        }
    }
}
