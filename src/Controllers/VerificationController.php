<?php
namespace Televice\Controllers;

use DateTimeImmutable;
use Televice\Repositories\AuditLogRepository;
use Televice\Repositories\ConsentRepository;
use Televice\Repositories\DocumentRepository;
use Televice\Repositories\VerificationRepository;
use Televice\Services\DocumentService;
use Televice\Services\EmailService;
use Televice\Services\SecurityService;
use Televice\Support\View;

class VerificationController
{
    private VerificationRepository $verifications;
    private DocumentRepository $documents;
    private ConsentRepository $consents;
    private AuditLogRepository $auditLogs;
    private DocumentService $documentService;
    private SecurityService $securityService;
    private EmailService $emailService;

    public function __construct()
    {
        $this->verifications = new VerificationRepository();
        $this->documents = new DocumentRepository();
        $this->consents = new ConsentRepository();
        $this->auditLogs = new AuditLogRepository();
        $this->documentService = new DocumentService($this->documents);
        $this->securityService = new SecurityService(
            new \Televice\Repositories\IdempotencyRepository(),
            new \Televice\Repositories\RateLimitRepository()
        );
        $this->emailService = new EmailService(new \Televice\Repositories\EmailLogRepository());
    }

    public function home(): string
    {
        ob_start();
        View::render('home');
        return ob_get_clean();
    }

    public function consent(): string
    {
        ob_start();
        View::render('consent');
        return ob_get_clean();
    }

    public function storeConsent(): string
    {
        $email = $_POST['email'] ?? null;
        $idempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? ($_POST['idempotency_key'] ?? null);
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $this->securityService->assertRateLimit('consent:' . ($email ?? $ip));
        $this->securityService->assertIdempotency($idempotencyKey, 'consent:' . ($email ?? $ip));

        $_SESSION['consent'] = [
            'email' => $email,
            'accepted_at' => (new DateTimeImmutable('now', new \DateTimeZone('Asia/Bangkok')))->format('Y-m-d H:i:s'),
            'version' => '2023-10-televice-pdpa',
        ];

        if ($email) {
            $hash = hash('sha256', $email . $ip . $agent . $_SESSION['consent']['accepted_at']);
            $this->consents->record([
                'email' => $email,
                'consent_version' => $_SESSION['consent']['version'],
                'ip_address' => $ip,
                'user_agent' => $agent,
                'hash' => $hash,
            ]);

            $this->emailService->send('consent_accepted', $email, 'ยืนยันการยอมรับข้อตกลง Televice', '<p>ขอบคุณที่ยอมรับข้อตกลงการคุ้มครองข้อมูลส่วนบุคคล</p>');
        }

        $this->auditLogs->log('user', null, 'consent.accepted', ['email' => $email]);

        header('Location: /account');
        return '';
    }

    public function account(): string
    {
        ob_start();
        View::render('account');
        return ob_get_clean();
    }

    public function validateAccount(): string
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
        $idempotencyKey = $_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? ($_POST['idempotency_key'] ?? null);
        $this->securityService->assertRateLimit('account:' . $email);
        $this->securityService->assertIdempotency($idempotencyKey, 'account:' . $email);

        $existing = $this->verifications->findByEmail($email);
        $open = null;
        foreach ($existing as $item) {
            if ($item['status'] === 'approved' && $item['deleted_at'] === null) {
                header('Location: /account?error=already_verified');
                exit;
            }
            if (in_array($item['status'], ['draft', 'pending', 'review', 'need_more_docs'], true)) {
                $open = $item;
            }
        }
        if ($open) {
            header('Location: /status?email=' . urlencode($email) . '&phone=' . urlencode($phone) . '&notice=open_case');
            exit;
        }

        $_SESSION['account'] = compact('email', 'phone');
        $_SESSION['phone_usage_count'] = $this->verifications->countPhoneUsage($phone, $email);
        header('Location: /applicant-type');
        return '';
    }

    public function selectType(): string
    {
        $phoneCount = $_SESSION['phone_usage_count'] ?? 0;
        ob_start();
        View::render('applicant-type', ['phoneCount' => $phoneCount]);
        return ob_get_clean();
    }

    public function storeType(): string
    {
        $type = $_POST['applicant_type'] ?? null;
        if (!in_array($type, ['individual', 'corporate', 'government'], true)) {
            header('Location: /applicant-type?error=invalid_type');
            exit;
        }
        $_SESSION['applicant_type'] = $type;
        header('Location: /documents');
        return '';
    }

    public function documents(): string
    {
        $type = $_SESSION['applicant_type'] ?? null;
        if (!$type) {
            header('Location: /applicant-type');
            exit;
        }
        ob_start();
        View::render('documents', ['type' => $type]);
        return ob_get_clean();
    }

    public function uploadDocuments(): string
    {
        $type = $_SESSION['applicant_type'] ?? null;
        if (!$type) {
            header('Location: /applicant-type');
            exit;
        }

        $email = $_SESSION['account']['email'] ?? null;
        $phone = $_SESSION['account']['phone'] ?? null;
        $reference = strtoupper('TV' . date('YmdHis') . rand(100, 999));
        $applicationId = $_SESSION['application_id'] ?? null;
        if (!$applicationId) {
            $applicationId = $this->verifications->createDraft([
                'reference' => $reference,
                'email' => $email,
                'phone' => $phone,
                'applicant_type' => $type,
                'payload' => [],
            ]);
            $_SESSION['application_id'] = $applicationId;
            $_SESSION['reference'] = $reference;
        }

        $documents = $this->requiredDocuments($type);
        $payload = $_SESSION['documents'] ?? [];

        foreach ($documents as $key => $meta) {
            if (!empty($_FILES[$key]['name'])) {
                $document = $this->documentService->handleUpload($applicationId, $key, $_FILES[$key]);
                $payload[$key] = $document;
                $this->emailService->send('file_uploaded', $email, 'มีการอัปโหลดเอกสาร', '<p>ระบบได้รับเอกสาร ' . htmlspecialchars($meta['label']) . '</p>', $applicationId);
            } elseif ($meta['required'] && empty($payload[$key])) {
                header('Location: /documents?error=missing_' . $key);
                exit;
            }
        }

        $this->verifications->updatePayload($applicationId, $payload);
        $_SESSION['documents'] = $payload;

        header('Location: /summary');
        return '';
    }

    public function summary(): string
    {
        $type = $_SESSION['applicant_type'] ?? null;
        $documents = $_SESSION['documents'] ?? [];
        if (!$type || !$documents) {
            header('Location: /documents');
            exit;
        }

        $maskedPhone = $this->maskPhone($_SESSION['account']['phone'] ?? '');
        $maskedEmail = $this->maskEmail($_SESSION['account']['email'] ?? '');

        ob_start();
        View::render('summary', [
            'type' => $type,
            'documents' => $documents,
            'reference' => $_SESSION['reference'] ?? '',
            'email' => $maskedEmail,
            'phone' => $maskedPhone,
        ]);
        return ob_get_clean();
    }

    public function submit(): string
    {
        $signature = $_POST['signature_data'] ?? null;
        if (!$signature) {
            header('Location: /summary?error=signature_required');
            exit;
        }
        $applicationId = $_SESSION['application_id'] ?? null;
        if (!$applicationId) {
            header('Location: /account');
            exit;
        }

        $payload = $_SESSION['documents'] ?? [];
        $payload['signature'] = $signature;
        $this->verifications->markSubmitted($applicationId, $payload);

        $email = $_SESSION['account']['email'] ?? '';
        $this->emailService->send('submitted', $email, 'รับคำขอยืนยันตัวตนแล้ว', '<p>หมายเลขคำขอ ' . htmlspecialchars($_SESSION['reference'] ?? '') . '</p>', $applicationId);

        $this->auditLogs->log('user', null, 'application.submitted', [
            'application_id' => $applicationId,
            'reference' => $_SESSION['reference'] ?? null,
        ]);

        header('Location: /submitted');
        return '';
    }

    public function submitted(): string
    {
        $reference = $_SESSION['reference'] ?? '';
        $sla = 'ตรวจสอบภายใน 1 วันทำการ (ส่งหลัง 16:00 น. ตรวจวันถัดไป)';
        ob_start();
        View::render('submitted', compact('reference', 'sla'));
        return ob_get_clean();
    }

    public function status(): string
    {
        $email = $_GET['email'] ?? '';
        $phone = $_GET['phone'] ?? '';
        $notice = $_GET['notice'] ?? null;
        ob_start();
        View::render('status', compact('email', 'phone', 'notice'));
        return ob_get_clean();
    }

    public function statusSearch(): string
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
        $cases = $this->verifications->findByContact($email, $phone);
        ob_start();
        View::render('status-results', [
            'cases' => $cases,
            'email' => $email,
            'phone' => $phone,
        ]);
        return ob_get_clean();
    }

    private function requiredDocuments(string $type): array
    {
        return match ($type) {
            'individual' => [
                'national_id_front' => ['label' => 'บัตรประชาชน (หน้า)', 'required' => true],
                'national_id_back' => ['label' => 'บัตรประชาชน (หลัง)', 'required' => true],
                'house_registration' => ['label' => 'ทะเบียนบ้าน', 'required' => false],
                'name_change' => ['label' => 'เอกสารเปลี่ยนชื่อ-สกุล', 'required' => false],
            ],
            'corporate' => [
                'certificate' => ['label' => 'หนังสือรับรอง ≤ 6 เดือน', 'required' => true],
                'shareholders' => ['label' => 'บอจ.5 / ผู้ถือหุ้นล่าสุด', 'required' => false],
                'vat' => ['label' => 'ภ.พ.20 (ถ้ามี)', 'required' => false],
                'power_of_attorney' => ['label' => 'หนังสือมอบอำนาจ (ถ้ามอบสิทธิ์)', 'required' => false],
                'authorized_id' => ['label' => 'บัตร ปชช. ผู้มีอำนาจลงนาม', 'required' => true],
            ],
            'government' => [
                'official_letter' => ['label' => 'หนังสือราชการหัวกระดาษตราครุฑ', 'required' => true],
                'gov_power_of_attorney' => ['label' => 'หนังสือมอบอำนาจ (ถ้ามี)', 'required' => false],
                'signer_id' => ['label' => 'บัตรข้าราชการ/บัตร ปชช. ผู้ลงนาม', 'required' => true],
            ],
            default => [],
        };
    }

    private function maskEmail(string $email): string
    {
        if (!$email) {
            return '';
        }
        [$name, $domain] = explode('@', $email);
        $maskedName = substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 2, 0));
        return $maskedName . '@' . $domain;
    }

    private function maskPhone(string $phone): string
    {
        if (strlen($phone) < 4) {
            return $phone;
        }
        return str_repeat('*', strlen($phone) - 4) . substr($phone, -4);
    }
}
