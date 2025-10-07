<?php
use Televice\Controllers\AdminController;
use Televice\Controllers\VerificationController;
use Televice\Support\Router;
use Televice\Support\TrafficLogger;

require_once __DIR__ . '/../src/bootstrap.php';

session_start();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
TrafficLogger::log($path, $_SERVER['REQUEST_METHOD'], $_SESSION['account']['email'] ?? null);

if (str_starts_with($path, '/cpmn')) {
    $adminController = new AdminController();
    echo $adminController->handle($path, $_SERVER['REQUEST_METHOD']);
    return;
}

$router = new Router();
$controller = new VerificationController();

$router->get('/', [$controller, 'home']);
$router->get('/consent', [$controller, 'consent']);
$router->post('/consent', [$controller, 'storeConsent']);
$router->get('/account', [$controller, 'account']);
$router->post('/account', [$controller, 'validateAccount']);
$router->get('/applicant-type', [$controller, 'selectType']);
$router->post('/applicant-type', [$controller, 'storeType']);
$router->get('/documents', [$controller, 'documents']);
$router->post('/documents', [$controller, 'uploadDocuments']);
$router->get('/summary', [$controller, 'summary']);
$router->post('/summary', [$controller, 'submit']);
$router->get('/submitted', [$controller, 'submitted']);
$router->get('/status', [$controller, 'status']);
$router->post('/status', [$controller, 'statusSearch']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
