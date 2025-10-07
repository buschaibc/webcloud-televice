<?php
namespace Televice\Support;

class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../../resources/views/' . $template . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException('View not found: ' . $template);
        }
        include $viewPath;
    }
}
