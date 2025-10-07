<?php $config = require __DIR__ . '/../../config.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['app_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <header class="bg-blue-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold">เทเลไวซ์ บิสิเนส — ระบบยืนยันตัวตน</h1>
            <p class="text-sm mt-2">ให้บริการเฉพาะประเทศไทย | เวลามาตรฐาน <?= htmlspecialchars($config['timezone']) ?></p>
        </div>
    </header>
    <main class="max-w-6xl mx-auto px-4 py-6">
        <?= $slot ?? '' ?>
    </main>
    <footer class="bg-blue-900 text-white mt-12">
        <div class="max-w-6xl mx-auto px-4 py-4 text-sm">
            &copy; <?= date('Y') ?> Televice Business. เก็บบันทึกการใช้งาน ≥ 90 วัน ตามมาตรา 26.
        </div>
    </footer>
</body>
</html>
