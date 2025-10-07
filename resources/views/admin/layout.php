<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Televice Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100 min-h-screen">
    <header class="bg-slate-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">Televice Verification Admin</h1>
            <?php if (!empty($_SESSION['admin_authenticated'])): ?>
                <nav class="flex items-center gap-4 text-sm">
                    <a href="/cpmn/dashboard" class="hover:underline">แดชบอร์ด</a>
                    <a href="/cpmn/cases" class="hover:underline">รายการคำขอ</a>
                    <a href="/cpmn/settings" class="hover:underline">การตั้งค่า</a>
                    <a href="/cpmn/logout" class="hover:underline text-red-300">ออกจากระบบ</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>
    <main class="max-w-6xl mx-auto px-4 py-8">
        <?= $slot ?? '' ?>
    </main>
</body>
</html>
