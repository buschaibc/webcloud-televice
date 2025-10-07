<?php
namespace Televice\Services;

use Exception;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use setasign\Fpdi\Fpdi;
use Televice\Repositories\DocumentRepository;
use Televice\Support\Container;

class DocumentService
{
    public function __construct(private DocumentRepository $documents)
    {
    }

    public function handleUpload(int $applicationId, string $documentKey, array $file): array
    {
        $config = Container::get('config');
        $secureDir = rtrim($config['storage']['secure'], '/');
        $publicDir = rtrim($config['storage']['public'], '/');
        if (!is_dir($secureDir)) {
            mkdir($secureDir, 0770, true);
        }
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0770, true);
        }

        $filename = uniqid($documentKey . '_') . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file['name']);
        $securePath = $secureDir . '/' . $filename;
        $publicPath = $publicDir . '/' . $filename;

        $moved = false;
        if (is_uploaded_file($file['tmp_name'])) {
            $moved = move_uploaded_file($file['tmp_name'], $securePath);
        }
        if (!$moved) {
            $moved = rename($file['tmp_name'], $securePath);
        }
        if (!$moved) {
            throw new Exception('ไม่สามารถบันทึกไฟล์ได้');
        }

        $originalHash = hash_file('sha256', $securePath);
        $this->encryptFile($securePath, $config['security']['encryption_key']);
        $this->generateWatermark($securePath, $publicPath, $config['security']['encryption_key']);

        $document = [
            'document_key' => $documentKey,
            'original_path' => $securePath,
            'watermarked_path' => $publicPath,
            'filename' => $file['name'],
            'mime_type' => $file['type'] ?? mime_content_type($securePath),
            'file_size' => $file['size'],
            'hash' => $originalHash,
        ];

        $this->documents->create($applicationId, $document);

        return $document;
    }

    private function encryptFile(string $path, string $key): void
    {
        $contents = file_get_contents($path);
        $iv = random_bytes(16);
        $cipher = openssl_encrypt($contents, 'AES-256-CBC', substr(hash('sha256', $key, true), 0, 32), OPENSSL_RAW_DATA, $iv);
        file_put_contents($path, base64_encode($iv . $cipher));
    }

    private function generateWatermark(string $securePath, string $publicPath, string $key): void
    {
        $tmpPath = sys_get_temp_dir() . '/' . uniqid('televice', true);
        $decrypted = $this->decryptFile($securePath, $key);
        file_put_contents($tmpPath, $decrypted);

        $mime = mime_content_type($tmpPath);
        if (str_contains($mime, 'pdf')) {
            $this->watermarkPdf($tmpPath, $publicPath);
        } else {
            $this->watermarkImage($tmpPath, $publicPath);
        }

        unlink($tmpPath);
    }

    private function decryptFile(string $path, string $key): string
    {
        $encoded = file_get_contents($path);
        $raw = base64_decode($encoded);
        $iv = substr($raw, 0, 16);
        $cipher = substr($raw, 16);
        return openssl_decrypt($cipher, 'AES-256-CBC', substr(hash('sha256', $key, true), 0, 32), OPENSSL_RAW_DATA, $iv) ?: '';
    }

    private function watermarkImage(string $input, string $output): void
    {
        if (class_exists(Imagick::class)) {
            $imagick = new Imagick($input);
            $draw = new ImagickDraw();
            $draw->setFillColor(new ImagickPixel('rgba(255,255,255,0.2)'));
            $draw->setFontSize(36);
            $imagick->setImageFormat('png');
            $geometry = $imagick->getImageGeometry();

            $text = 'ใช้สำหรับสมัครใช้บริการกับเทเลไวซ์ บิสิเนส เท่านั้น';
            $draw->setGravity(Imagick::GRAVITY_CENTER);
            $draw->setTextAntialias(true);
            $draw->setStrokeColor(new ImagickPixel('rgba(0,0,0,0.1)'));
            $draw->setStrokeWidth(1);
            $imagick->annotateImage($draw, $geometry['width'] / 2, $geometry['height'] / 2, 45, $text);
            $imagick->writeImage($output);
            $imagick->clear();
            $imagick->destroy();
            return;
        }

        $image = imagecreatefromstring(file_get_contents($input));
        $width = imagesx($image);
        $height = imagesy($image);
        $overlay = imagecreatetruecolor($width, $height);
        imagesavealpha($overlay, true);
        $transparent = imagecolorallocatealpha($overlay, 0, 0, 0, 127);
        imagefill($overlay, 0, 0, $transparent);

        $color = imagecolorallocatealpha($overlay, 255, 255, 255, 105);
        $fontSize = 12;
        $text = 'ใช้สำหรับสมัครใช้บริการกับเทเลไวซ์ บิสิเนส เท่านั้น';
        imagestring($overlay, $fontSize, $width / 4, $height / 2, $text, $color);
        imagecopymerge($image, $overlay, 0, 0, 0, 0, $width, $height, 50);
        imagepng($image, $output);
        imagedestroy($image);
        imagedestroy($overlay);
    }

    private function watermarkPdf(string $input, string $output): void
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($input);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
            $pdf->SetFont('Helvetica', '', 14);
            $pdf->SetTextColor(180, 180, 180);
            $text = 'ใช้สำหรับสมัครใช้บริการกับเทเลไวซ์ บิสิเนส เท่านั้น';
            $margin = 20;
            for ($y = $margin; $y < $size['height']; $y += 40) {
                $pdf->SetXY($margin, $y);
                $pdf->Cell(0, 10, $text);
            }
        }
        $pdf->Output($output, 'F');
    }
}
