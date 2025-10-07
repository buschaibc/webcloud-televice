<?php
$typeLabels = [
    'individual' => 'บุคคลธรรมดา',
    'corporate' => 'นิติบุคคล',
    'government' => 'หน่วยงานรัฐ/ภาครัฐ',
];
$documents = [
    'individual' => [
        'national_id_front' => ['label' => 'บัตรประชาชน (หน้า)', 'required' => true],
        'national_id_back' => ['label' => 'บัตรประชาชน (หลัง)', 'required' => true],
        'house_registration' => ['label' => 'ทะเบียนบ้าน', 'required' => false],
        'name_change' => ['label' => 'เอกสารเปลี่ยนชื่อ-สกุล', 'required' => false],
    ],
    'corporate' => [
        'certificate' => ['label' => 'หนังสือรับรอง ≤ 6 เดือน', 'required' => true],
        'shareholders' => ['label' => 'บอจ.5 / ผู้ถือหุ้นล่าสุด', 'required' => false],
        'vat' => ['label' => 'ภ.พ.20 (ถ้ามี)', 'required' => false],
        'power_of_attorney' => ['label' => 'หนังสือมอบอำนาจ (ถ้ามอบสิทธิ์)', 'required' => false],
        'authorized_id' => ['label' => 'บัตรประชาชนผู้มีอำนาจลงนาม', 'required' => true],
    ],
    'government' => [
        'official_letter' => ['label' => 'หนังสือราชการหัวกระดาษตราครุฑ', 'required' => true],
        'gov_power_of_attorney' => ['label' => 'หนังสือมอบอำนาจ (ถ้ามี)', 'required' => false],
        'signer_id' => ['label' => 'บัตรข้าราชการ/บัตรประชาชนผู้ลงนาม', 'required' => true],
    ],
];
$currentDocuments = $documents[$type] ?? [];
$error = $_GET['error'] ?? null;
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 uppercase tracking-wide">ขั้นตอนที่ 4 <span class="font-semibold text-slate-800">อัปโหลดเอกสาร</span></div>
        <div class="h-2 bg-slate-200 rounded-full mt-2">
            <div class="h-full bg-blue-600 rounded-full w-4/6"></div>
        </div>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">อัปโหลดเอกสารสำหรับ<?= htmlspecialchars($typeLabels[$type] ?? '') ?></h2>
    <p class="mt-3 text-slate-600">รองรับไฟล์ JPG, PNG, PDF &mdash; ระบบจะทำลายน้ำข้อความ "ใช้สำหรับสมัครใช้บริการกับเทเลไวซ์ บิสิเนส เท่านั้น" อัตโนมัติ และเก็บต้นฉบับแบบเข้ารหัส</p>
    <?php if ($error): ?>
        <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">กรุณาอัปโหลดเอกสารที่บังคับให้ครบถ้วน</div>
    <?php endif; ?>
    <form action="/documents" method="post" enctype="multipart/form-data" class="mt-6 space-y-6">
        <?php foreach ($currentDocuments as $key => $doc): ?>
            <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                <label class="block text-sm font-semibold text-slate-700">
                    <?= htmlspecialchars($doc['label']) ?>
                    <?php if ($doc['required']): ?><span class="text-red-500">*</span><?php endif; ?>
                </label>
                <input type="file" name="<?= $key ?>" accept=".jpg,.jpeg,.png,.pdf" class="mt-2 w-full text-sm" <?= $doc['required'] ? 'required' : '' ?>>
                <p class="text-xs text-slate-500 mt-1">รองรับขนาดไม่เกิน 10 MB ต่อไฟล์</p>
            </div>
        <?php endforeach; ?>
        <div class="flex items-start gap-3 text-sm text-slate-600 bg-amber-50 border border-amber-200 p-4 rounded-lg">
            <span class="font-semibold text-amber-700">คำเตือน:</span>
            <p>กรุณาปิดข้อมูลอ่อนไหวที่ไม่จำเป็นก่อนส่ง และตรวจสอบความชัดเจนของเอกสารทุกฉบับ</p>
        </div>
        <div>
            <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">ตรวจสอบข้อมูล</button>
        </div>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
