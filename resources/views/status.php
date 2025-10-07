<?php
$noticeMessage = null;
if (($notice ?? null) === 'open_case') {
    $noticeMessage = 'คุณมีคำขอที่กำลังดำเนินการอยู่ โปรดติดตามความคืบหน้าหรืออัปโหลดเอกสารเพิ่มเติมจากหน้าติดตามสถานะ';
}
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-slate-800">ติดตามสถานะคำขอ</h2>
    <p class="mt-3 text-slate-600">กรอกอีเมลและเบอร์โทรศัพท์เดียวกับที่ใช้ยื่นคำขอ เพื่อดูรายการคำขอและไทม์ไลน์ล่าสุด</p>
    <?php if ($noticeMessage): ?>
        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg"><?= htmlspecialchars($noticeMessage) ?></div>
    <?php endif; ?>
    <form action="/status" method="post" class="mt-6 grid md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-slate-700">อีเมล</label>
            <input required type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" class="mt-1 w-full border rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">เบอร์โทรศัพท์</label>
            <input required type="tel" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" class="mt-1 w-full border rounded-lg px-4 py-2">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">ค้นหาคำขอ</button>
        </div>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
