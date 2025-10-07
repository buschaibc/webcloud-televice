<?php
$slot = <<<HTML
<div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8 mt-12">
    <h2 class="text-2xl font-bold text-slate-800 text-center">เข้าสู่ระบบผู้ดูแล</h2>
    <p class="text-center text-sm text-slate-500 mt-2">URL พิเศษ /cpmn เท่านั้น</p>
    <?php if (($_GET['error'] ?? null) === 'invalid'): ?>
        <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</div>
    <?php endif; ?>
    <form action="/cpmn/login" method="post" class="mt-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700">ชื่อผู้ใช้</label>
            <input type="text" name="username" required class="mt-1 w-full border rounded-lg px-4 py-2" value="televice">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">รหัสผ่าน</label>
            <input type="password" name="password" required class="mt-1 w-full border rounded-lg px-4 py-2" value="Admin1234">
        </div>
        <button type="submit" class="w-full bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-800">เข้าสู่ระบบ</button>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
