<?php
$statusOptions = ['draft', 'pending', 'review', 'need_more_docs', 'approved', 'rejected', 'expired'];
$slot = <<<HTML
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">คำขอ #<?= htmlspecialchars($case['reference'] ?? ('TV-' . $case['id'])) ?></h2>
                <p class="text-sm text-slate-500">สถานะปัจจุบัน: <span class="font-semibold text-blue-700"><?= htmlspecialchars($case['status']) ?></span></p>
            </div>
            <a href="/cpmn/cases" class="text-blue-700 hover:underline text-sm">ย้อนกลับ</a>
        </div>
        <div class="mt-4 grid md:grid-cols-2 gap-4 text-sm text-slate-600">
            <p><span class="font-semibold">อีเมล:</span> <?= htmlspecialchars($case['email']) ?></p>
            <p><span class="font-semibold">เบอร์โทร:</span> <?= htmlspecialchars($case['phone']) ?></p>
            <p><span class="font-semibold">สร้างเมื่อ:</span> <?= htmlspecialchars($case['created_at']) ?></p>
            <p><span class="font-semibold">อัปเดตล่าสุด:</span> <?= htmlspecialchars($case['updated_at']) ?></p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-slate-800">เอกสารที่อัปโหลด</h3>
        <ul class="mt-4 space-y-3 text-sm text-slate-600">
            <?php foreach ($docs as $doc): ?>
                <li class="border border-slate-200 rounded-lg p-3">
                    <p class="font-semibold"><?= htmlspecialchars($doc['document_key']) ?></p>
                    <p class="text-xs text-slate-400">ไฟล์ลายน้ำ: <?= htmlspecialchars($doc['watermarked_path']) ?></p>
                    <p class="text-xs text-slate-400">ไฟล์เข้ารหัส: <?= htmlspecialchars($doc['original_path']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-slate-800">อัปเดตสถานะ</h3>
        <form action="/cpmn/case-update" method="post" class="space-y-4">
            <input type="hidden" name="application_id" value="<?= (int) $case['id'] ?>">
            <div>
                <label class="block text-sm font-semibold text-slate-700">เลือกสถานะ</label>
                <select name="status" class="mt-1 w-full border rounded-lg px-4 py-2">
                    <?php foreach ($statusOptions as $option): ?>
                        <option value="<?= $option ?>" <?= $case['status'] === $option ? 'selected' : '' ?>><?= strtoupper($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700">หมายเหตุ</label>
                <textarea name="remark" rows="3" class="mt-1 w-full border rounded-lg px-4 py-2" placeholder="รายละเอียดเพิ่มเติม"></textarea>
            </div>
            <div class="flex flex-col md:flex-row gap-3">
                <button name="action" value="update" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">บันทึกสถานะ</button>
                <button name="action" value="delete_soft" class="bg-amber-600 text-white px-4 py-2 rounded-lg font-semibold" onclick="return confirm('ยืนยันลบแบบ Soft Delete?')">Soft Delete</button>
                <button name="action" value="delete_hard" class="bg-red-700 text-white px-4 py-2 rounded-lg font-semibold" onclick="return confirm('ยืนยันลบถาวร (เฉพาะ Super Admin)?')">Hard Delete</button>
            </div>
        </form>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-slate-800">ประวัติสถานะ</h3>
        <ul class="mt-4 space-y-2 text-sm text-slate-600">
            <?php foreach ($history as $item): ?>
                <li class="border border-slate-200 rounded-lg p-3">
                    <p class="font-semibold"><?= strtoupper($item['status']) ?></p>
                    <p class="text-xs text-slate-400"><?= htmlspecialchars($item['created_at']) ?></p>
                    <p><?= htmlspecialchars($item['remark'] ?? '-') ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
HTML;
include __DIR__ . '/layout.php';
