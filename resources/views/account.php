<?php
$error = $_GET['error'] ?? null;
$warning = null;
if ($error === 'already_verified') {
    $warning = 'อีเมลนี้ได้รับการยืนยันแล้วและบัญชียังใช้งานอยู่ ไม่สามารถยื่นคำขอใหม่';
}
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 uppercase tracking-wide">ขั้นตอนที่ 2 <span class="font-semibold text-slate-800">ตรวจสอบบัญชี</span></div>
        <div class="h-2 bg-slate-200 rounded-full mt-2">
            <div class="h-full bg-blue-600 rounded-full w-2/6"></div>
        </div>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">กรอกอีเมลและเบอร์โทร</h2>
    <p class="mt-3 text-slate-600">กรุณาใช้ข้อมูลเดียวกับที่ใช้งานใน Televice Business Portal ระบบจะตรวจสอบสิทธิ์เพื่อป้องกันการยื่นซ้ำ</p>
    <?php if ($warning): ?>
        <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?= htmlspecialchars($warning) ?></div>
    <?php endif; ?>
    <form action="/account" method="post" class="mt-6 grid md:grid-cols-2 gap-6">
        <input type="hidden" name="idempotency_key" value="<?= bin2hex(random_bytes(16)) ?>">
        <div>
            <label class="block text-sm font-medium text-slate-700">อีเมล</label>
            <input required type="email" name="email" class="mt-1 w-full border rounded-lg px-4 py-2" placeholder="example@televice.co.th">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">เบอร์โทรศัพท์</label>
            <input required type="tel" name="phone" class="mt-1 w-full border rounded-lg px-4 py-2" placeholder="0812345678">
        </div>
        <div class="md:col-span-2 text-sm text-slate-500">หมายเหตุ: ถ้าเบอร์นี้ถูกใช้กับหลายบัญชี ระบบจะแสดงจำนวนที่เกี่ยวข้องโดยไม่เปิดเผยอีเมลอื่น</div>
        <div class="md:col-span-2">
            <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">ดำเนินการต่อ</button>
        </div>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
