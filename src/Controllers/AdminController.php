<?php
namespace Televice\Controllers;

use Televice\Repositories\AuditLogRepository;
use Televice\Repositories\EmailLogRepository;
use Televice\Repositories\VerificationRepository;
use Televice\Services\EmailService;
use Televice\Support\Container;
use Televice\Support\Database;
use Televice\Support\View;

class AdminController
{
    private VerificationRepository $verifications;
    private AuditLogRepository $auditLogs;
    private EmailService $emailService;

    public function __construct()
    {
        $this->verifications = new VerificationRepository();
        $this->auditLogs = new AuditLogRepository();
        $this->emailService = new EmailService(new EmailLogRepository());
    }

    public function handle(string $path, string $method): string
    {
        $segments = explode('/', trim($path, '/'));
        if ($segments[0] !== 'cpmn') {
            http_response_code(404);
            return 'Not Found';
        }
        if (!isset($_SESSION['admin_authenticated']) && $segments[1] !== 'login') {
            header('Location: /cpmn/login');
            exit;
        }

        return match ($segments[1] ?? '') {
            '' => $this->loginForm(),
            'login' => $method === 'POST' ? $this->login() : $this->loginForm(),
            'logout' => $this->logout(),
            'dashboard' => $this->dashboard(),
            'cases' => $this->casesList(),
            'case' => $this->caseDetail($segments[2] ?? null),
            'case-update' => $method === 'POST' ? $this->caseUpdate() : 'Method Not Allowed',
            'settings' => $method === 'POST' ? $this->updateSettings() : $this->settings(),
            default => 'Not Found',
        };
    }

    private function loginForm(): string
    {
        if (isset($_SESSION['admin_authenticated'])) {
            header('Location: /cpmn/dashboard');
            exit;
        }
        ob_start();
        View::render('admin/login');
        return ob_get_clean();
    }

    private function login(): string
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $stmt = Database::connection()->prepare('SELECT * FROM admin_users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        if (!$user || $user['password'] !== $password) {
            header('Location: /cpmn/login?error=invalid');
            exit;
        }
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $this->auditLogs->log('admin', (int) $user['id'], 'admin.login', []);
        header('Location: /cpmn/dashboard');
        return '';
    }

    private function logout(): string
    {
        $this->auditLogs->log('admin', $_SESSION['admin_user_id'] ?? null, 'admin.logout', []);
        unset($_SESSION['admin_authenticated'], $_SESSION['admin_user_id'], $_SESSION['admin_username']);
        header('Location: /cpmn/login');
        return '';
    }

    private function dashboard(): string
    {
        $pdo = Database::connection();
        $total = $pdo->query('SELECT COUNT(*) FROM applications WHERE deleted_at IS NULL')->fetchColumn();
        $pending = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending' AND deleted_at IS NULL")->fetchColumn();
        $needMore = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'need_more_docs' AND deleted_at IS NULL")->fetchColumn();
        $approved = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'approved' AND deleted_at IS NULL")->fetchColumn();
        $emailStatus = Container::get('config')['email_system_enabled'] ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        ob_start();
        View::render('admin/dashboard', compact('total', 'pending', 'needMore', 'approved', 'emailStatus'));
        return ob_get_clean();
    }

    private function casesList(): string
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT * FROM applications WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 50');
        $cases = $stmt->fetchAll();
        ob_start();
        View::render('admin/cases', compact('cases'));
        return ob_get_clean();
    }

    private function caseDetail(?string $id): string
    {
        if (!$id) {
            header('Location: /cpmn/cases');
            exit;
        }
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM applications WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $case = $stmt->fetch();
        if (!$case) {
            header('Location: /cpmn/cases');
            exit;
        }
        $documents = $pdo->prepare('SELECT * FROM documents WHERE application_id = :id');
        $documents->execute(['id' => $id]);
        $docs = $documents->fetchAll();
        $historyStmt = $pdo->prepare('SELECT * FROM application_status_history WHERE application_id = :id ORDER BY created_at DESC');
        $historyStmt->execute(['id' => $id]);
        $history = $historyStmt->fetchAll();
        ob_start();
        View::render('admin/case-detail', compact('case', 'docs', 'history'));
        return ob_get_clean();
    }

    private function caseUpdate(): string
    {
        $applicationId = (int) ($_POST['application_id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        $remark = $_POST['remark'] ?? null;
        $action = $_POST['action'] ?? 'update';
        $pdo = Database::connection();
        $emailStmt = $pdo->prepare('SELECT email FROM applications WHERE id = :id');
        $emailStmt->execute(['id' => $applicationId]);
        $targetEmail = $emailStmt->fetchColumn() ?: null;
        if ($action === 'delete_soft') {
            $stmt = $pdo->prepare('UPDATE applications SET deleted_at = UTC_TIMESTAMP() WHERE id = :id');
            $stmt->execute(['id' => $applicationId]);
            $this->auditLogs->log('admin', $_SESSION['admin_user_id'] ?? null, 'case.soft_delete', ['application_id' => $applicationId]);
        } elseif ($action === 'delete_hard') {
            $pdo->prepare('DELETE FROM applications WHERE id = :id')->execute(['id' => $applicationId]);
            $pdo->prepare('DELETE FROM documents WHERE application_id = :id')->execute(['id' => $applicationId]);
            $this->auditLogs->log('admin', $_SESSION['admin_user_id'] ?? null, 'case.hard_delete', ['application_id' => $applicationId]);
        } else {
            $stmt = $pdo->prepare('UPDATE applications SET status = :status, updated_at = UTC_TIMESTAMP() WHERE id = :id');
            $stmt->execute(['status' => $status, 'id' => $applicationId]);
            $pdo->prepare('INSERT INTO application_status_history (application_id, status, remark, created_at) VALUES (:id, :status, :remark, UTC_TIMESTAMP())')->execute([
                'id' => $applicationId,
                'status' => $status,
                'remark' => $remark,
            ]);
            $this->auditLogs->log('admin', $_SESSION['admin_user_id'] ?? null, 'case.status_changed', ['application_id' => $applicationId, 'status' => $status]);
            if ($targetEmail) {
                $subject = match ($status) {
                    'approved' => 'คำขอยืนยันตัวตนได้รับการอนุมัติ',
                    'rejected' => 'คำขอยืนยันตัวตนถูกปฏิเสธ',
                    'need_more_docs' => 'โปรดส่งเอกสารเพิ่มเติมสำหรับ Televice',
                    default => 'มีการอัปเดตสถานะคำขอ Televice',
                };
                $event = match ($status) {
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'need_more_docs' => 'more_docs_requested',
                    default => 'status_changed',
                };
                $body = '<p>สถานะคำขอของคุณ: <strong>' . htmlspecialchars(strtoupper($status)) . '</strong></p>';
                if ($remark) {
                    $body .= '<p>หมายเหตุ: ' . nl2br(htmlspecialchars($remark)) . '</p>';
                }
                $this->emailService->send($event, $targetEmail, $subject, $body, $applicationId);
            }
        }
        header('Location: /cpmn/case/' . $applicationId);
        return '';
    }

    private function settings(): string
    {
        $pdo = Database::connection();
        $settings = $pdo->query('SELECT * FROM system_settings LIMIT 1')->fetch();
        ob_start();
        View::render('admin/settings', compact('settings'));
        return ob_get_clean();
    }

    private function updateSettings(): string
    {
        $emailEnabled = isset($_POST['email_system_enabled']) ? 1 : 0;
        $cutoff = (int) ($_POST['cutoff_hour'] ?? 16);
        $businessHours = $_POST['business_hours'] ?? 'จันทร์-ศุกร์ 09:00-18:00';
        $announcement = $_POST['announcement'] ?? '';
        $password = $_POST['new_password'] ?? null;
        $pdo = Database::connection();
        $pdo->prepare('UPDATE system_settings SET email_system_enabled = :email_enabled, cutoff_hour = :cutoff, business_hours = :business_hours, announcement = :announcement, updated_at = UTC_TIMESTAMP()')->execute([
            'email_enabled' => $emailEnabled,
            'cutoff' => $cutoff,
            'business_hours' => $businessHours,
            'announcement' => $announcement,
        ]);
        $config = Container::get('config');
        $config['email_system_enabled'] = (bool) $emailEnabled;
        $config['business_hours']['cutoff_hour'] = $cutoff;
        Container::set('config', $config);
        if ($password) {
            $stmt = $pdo->prepare('UPDATE admin_users SET password = :password, force_password_change = 0 WHERE id = :id');
            $stmt->execute(['password' => $password, 'id' => $_SESSION['admin_user_id']]);
        }
        $this->auditLogs->log('admin', $_SESSION['admin_user_id'] ?? null, 'settings.updated', []);
        header('Location: /cpmn/settings?saved=1');
        return '';
    }
}
