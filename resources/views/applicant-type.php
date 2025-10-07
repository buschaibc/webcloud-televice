<?php
$phoneCount = $phoneCount ?? 0;
$phoneNotice = $phoneCount > 0 ? "หมายเลขนี้ถูกใช้ในบัญชีอื่นอีก {$phoneCount} รายการ" : null;
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 uppercase tracking-wide">ขั้นตอนที่ 3 <span class="font-semibold text-slate-800">เลือกประเภทผู้ใช้</span></div>
        <div class="h-2 bg-slate-200 rounded-full mt-2">
            <div class="h-full bg-blue-600 rounded-full w-3/6"></div>
        </div>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">เลือกรูปแบบการยืนยันตัวตน</h2>
    <p class="mt-3 text-slate-600">ระบบรองรับ 3 ประเภท พร้อมรายการเอกสารที่ต้องใช้</p>
    <?php if ($phoneNotice): ?>
        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg"><?= htmlspecialchars($phoneNotice) ?></div>
    <?php endif; ?>
    <form action="/applicant-type" method="post" class="mt-6 grid gap-6 md:grid-cols-3">
        <button type="submit" name="applicant_type" value="individual" class="group border border-slate-200 rounded-xl p-6 text-left hover:border-blue-600 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-slate-800 group-hover:text-blue-700">บุคคลธรรมดา</h3>
            <ul class="text-sm text-slate-600 list-disc list-inside mt-3 space-y-1">
                <li>บัตรประชาชน หน้า/หลัง (บังคับ)</li>
                <li>ทะเบียนบ้าน (ถ้ามี)</li>
                <li>เอกสารเปลี่ยนชื่อ-สกุล (ถ้ามี)</li>
            </ul>
        </button>
        <button type="submit" name="applicant_type" value="corporate" class="group border border-slate-200 rounded-xl p-6 text-left hover:border-blue-600 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-slate-800 group-hover:text-blue-700">นิติบุคคล</h3>
            <ul class="text-sm text-slate-600 list-disc list-inside mt-3 space-y-1">
                <li>หนังสือรับรอง ≤ 6 เดือน (บังคับ)</li>
                <li>บอจ.5 / ผู้ถือหุ้นล่าสุด</li>
                <li>ภ.พ.20 (ถ้ามี VAT)</li>
                <li>หนังสือมอบอำนาจ (ถ้ามอบสิทธิ์)</li>
                <li>บัตรประชาชนผู้มีอำนาจลงนาม (บังคับ)</li>
            </ul>
        </button>
        <button type="submit" name="applicant_type" value="government" class="group border border-slate-200 rounded-xl p-6 text-left hover:border-blue-600 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-slate-800 group-hover:text-blue-700">หน่วยงานรัฐ</h3>
            <ul class="text-sm text-slate-600 list-disc list-inside mt-3 space-y-1">
                <li>หนังสือราชการหัวกระดาษตราครุฑ (บังคับ)</li>
                <li>หนังสือมอบอำนาจ (ถ้ามี)</li>
                <li>บัตรข้าราชการ/บัตรประชาชนผู้ลงนาม (บังคับ)</li>
            </ul>
        </button>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
