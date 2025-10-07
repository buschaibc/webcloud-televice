<?php
return [
    'app_name' => 'Televice Business Verification e-KYC',
    'timezone' => 'Asia/Bangkok',
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'database' => getenv('DB_DATABASE') ?: 'televice',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
    ],
    'storage' => [
        'secure' => __DIR__ . '/storage/secure',
        'public' => __DIR__ . '/storage/public',
    ],
    'uploads' => __DIR__ . '/uploads',
    'email_system_enabled' => getenv('EMAIL_SYSTEM_ENABLED') !== false ? filter_var(getenv('EMAIL_SYSTEM_ENABLED'), FILTER_VALIDATE_BOOLEAN) : true,
    'email' => [
        'host' => getenv('SMTP_HOST') ?: 'localhost',
        'port' => getenv('SMTP_PORT') ?: 25,
        'username' => getenv('SMTP_USERNAME') ?: null,
        'password' => getenv('SMTP_PASSWORD') ?: null,
        'encryption' => getenv('SMTP_ENCRYPTION') ?: null,
        'from_address' => getenv('SMTP_FROM_ADDRESS') ?: 'noreply@televice.co.th',
        'from_name' => getenv('SMTP_FROM_NAME') ?: 'Televice Verification'
    ],
    'security' => [
        'encryption_key' => getenv('FILE_ENCRYPTION_KEY') ?: 'change-me-secure-key-32-bytes',
        'idempotency_window' => 300,
        'rate_limit' => [
            'requests' => 10,
            'per_minutes' => 5,
        ],
    ],
    'business_hours' => [
        'cutoff_hour' => getenv('BUSINESS_CUTOFF_HOUR') ?: 16,
        'timezone' => 'Asia/Bangkok',
        'working_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
    ],
];
