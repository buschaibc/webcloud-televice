<?php
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-6 space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">การตั้งค่าระบบ</h2>
        <?php if (($_GET['saved'] ?? null) === '1'): ?>
            <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">บันทึกการตั้งค่าเรียบร้อย</div>
        <?php endif; ?>
    </div>
    <form action="/cpmn/settings" method="post" class="space-y-4">
        <div class="flex items-center gap-3">
            <input type="checkbox" id="email_system_enabled" name="email_system_enabled" <?= ($settings['email_system_enabled'] ?? 0) ? 'checked' : '' ?> class="w-4 h-4">
            <label for="email_system_enabled" class="text-sm text-slate-700">เปิดระบบส่งอีเมลธุรกรรม</label>
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">Cutoff Hour</label>
            <input type="number" name="cutoff_hour" value="<?= htmlspecialchars($settings['cutoff_hour'] ?? 16) ?>" class="mt-1 w-full border rounded-lg px-4 py-2" min="0" max="23">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">เวลาทำการ</label>
            <input type="text" name="business_hours" value="<?= htmlspecialchars($settings['business_hours'] ?? 'จันทร์-ศุกร์ 09:00-18:00') ?>" class="mt-1 w-full border rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700">ข้อความประกาศ</label>
            <textarea name="announcement" rows="3" class="mt-1 w-full border rounded-lg px-4 py-2"><?= htmlspecialchars($settings['announcement'] ?? '') ?></textarea>
        </div>
        <div class="border-t border-slate-200 pt-4">
            <h3 class="text-lg font-semibold text-slate-800">เปลี่ยนรหัสผ่านผู้ดูแล</h3>
            <p class="text-xs text-slate-500">รหัสผ่านจัดเก็บเป็นข้อความธรรมดาตามข้อกำหนด (ควรเปลี่ยนทันทีหลังเข้าใช้ครั้งแรก)</p>
            <input type="password" name="new_password" class="mt-2 w-full border rounded-lg px-4 py-2" placeholder="รหัสผ่านใหม่ (เว้นว่างหากไม่เปลี่ยน)">
        </div>
        <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">บันทึกการตั้งค่า</button>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
