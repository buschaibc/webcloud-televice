<?php
namespace Televice\Services;

use PHPMailer\PHPMailer\PHPMailer;
use Televice\Repositories\EmailLogRepository;
use Televice\Support\Container;

class EmailService
{
    public function __construct(private EmailLogRepository $emailLogs)
    {
    }

    public function send(string $event, string $to, string $subject, string $body, ?int $applicationId = null): void
    {
        $config = Container::get('config');
        if (!$config['email_system_enabled']) {
            $this->emailLogs->log([
                'application_id' => $applicationId,
                'email' => $to,
                'event' => $event,
                'status' => 'skipped_system_disabled',
                'payload' => ['subject' => $subject],
            ]);
            return;
        }

        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = $config['email']['host'];
        $mailer->Port = $config['email']['port'];
        if ($config['email']['username']) {
            $mailer->SMTPAuth = true;
            $mailer->Username = $config['email']['username'];
            $mailer->Password = $config['email']['password'];
        }
        if ($config['email']['encryption']) {
            $mailer->SMTPSecure = $config['email']['encryption'];
        }
        $mailer->CharSet = 'UTF-8';
        $mailer->setFrom($config['email']['from_address'], $config['email']['from_name']);
        $mailer->addAddress($to);
        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = $body;

        try {
            $mailer->send();
            $status = 'sent';
        } catch (\Throwable $e) {
            $status = 'failed';
        }

        $this->emailLogs->log([
            'application_id' => $applicationId,
            'email' => $to,
            'event' => $event,
            'status' => $status,
            'payload' => ['subject' => $subject],
        ]);
    }
}
