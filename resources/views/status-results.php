<?php
$slot = <<<HTML
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-slate-800">ผลการค้นหา</h2>
        <p class="mt-3 text-slate-600">อีเมล: <span class="font-semibold">{$email}</span> | เบอร์โทรศัพท์: <span class="font-semibold">{$phone}</span></p>
        <?php if (empty($cases)): ?>
            <div class="mt-6 p-6 border border-slate-200 rounded-lg bg-slate-50 text-slate-600">ไม่พบคำขอในระบบ</div>
        <?php else: ?>
            <div class="mt-6 space-y-4">
                <?php foreach ($cases as $case): ?>
                    <div class="border border-slate-200 rounded-lg p-6 bg-white shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <p class="text-sm text-slate-500">หมายเลขคำขอ</p>
                                <p class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($case['reference'] ?? ('TV-' . $case['id'])) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">สถานะปัจจุบัน</p>
                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold text-sm"><?= htmlspecialchars(strtoupper($case['status'])) ?></span>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-slate-600 space-y-1">
                            <p>อัปเดตล่าสุด: <?= htmlspecialchars($case['updated_at']) ?></p>
                            <p>ส่งคำขอ: <?= htmlspecialchars($case['submitted_at'] ?? '-') ?></p>
                        </div>
                        <?php if (($case['status'] ?? '') === 'need_more_docs'): ?>
                            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg">
                                กรุณาอัปโหลดเอกสารเพิ่มเติมโดยตอบกลับอีเมลที่ได้รับจากทีมงาน หรือเข้าสู่ระบบผู้ดูแล (ถ้ามีสิทธิ์)
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="mt-6">
            <a href="/status" class="text-blue-700 hover:underline">ค้นหาใหม่</a>
        </div>
    </div>
</div>
HTML;
include __DIR__ . '/layout.php';
