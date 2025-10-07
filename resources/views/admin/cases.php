<?php
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-slate-800">รายการคำขอ</h2>
    <p class="text-sm text-slate-500">แสดง 50 รายการล่าสุด</p>
    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-slate-600 uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">หมายเลขคำขอ</th>
                    <th class="px-4 py-2 text-left">อีเมล</th>
                    <th class="px-4 py-2 text-left">สถานะ</th>
                    <th class="px-4 py-2 text-left">สร้างเมื่อ</th>
                    <th class="px-4 py-2 text-left">ดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cases as $case): ?>
                    <tr class="border-b border-slate-100">
                        <td class="px-4 py-2 font-semibold text-slate-800"><?= htmlspecialchars($case['reference'] ?? ('TV-' . $case['id'])) ?></td>
                        <td class="px-4 py-2 text-slate-600"><?= htmlspecialchars($case['email']) ?></td>
                        <td class="px-4 py-2"><span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold"><?= htmlspecialchars($case['status']) ?></span></td>
                        <td class="px-4 py-2 text-slate-500"><?= htmlspecialchars($case['created_at']) ?></td>
                        <td class="px-4 py-2"><a class="text-blue-700 hover:underline" href="/cpmn/case/<?= $case['id'] ?>">รายละเอียด</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
HTML;
include __DIR__ . '/layout.php';
