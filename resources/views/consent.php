<?php
$pdpaItems = [
    'วัตถุประสงค์การเก็บข้อมูลเพื่อยืนยันตัวตนและปฏิบัติตามกฎหมายที่เกี่ยวข้อง',
    'ประเภทข้อมูลส่วนบุคคลที่เก็บรวบรวม เช่น ข้อมูลติดต่อ เอกสารยืนยันตัวตน',
    'วิธีการเก็บรวบรวมข้อมูลผ่านแบบฟอร์มออนไลน์และเอกสารที่ผู้ใช้ส่งให้',
    'ระยะเวลาในการเก็บรักษาข้อมูลไม่น้อยกว่า 10 ปี หรือตามที่กฎหมายกำหนด',
    'สิทธิของเจ้าของข้อมูล เช่น การเข้าถึง แก้ไข ลบ หรือระงับการใช้ข้อมูล',
    'การเปิดเผยข้อมูลให้หน่วยงานรัฐตามกฎหมายหรือคู่สัญญาที่จำเป็น',
    'มาตรการรักษาความมั่นคงปลอดภัยของข้อมูล เช่น การเข้ารหัสและควบคุมสิทธิ์เข้าถึง',
    'การโอนย้ายข้อมูลไปต่างประเทศ (ถ้ามี) จะทำภายใต้กรอบกฎหมายไทย',
    'การใช้ข้อมูลเพื่อการติดต่อและแจ้งเตือนสถานะคำขอบริการ',
    'การใช้คุกกี้และบันทึกการใช้งานระบบเพื่อความปลอดภัยตามมาตรา 26',
    'ข้อมูลผู้ประมวลผลภายนอก (Data Processor) ที่ได้รับมอบหมายจาก Televice',
    'ช่องทางการติดต่อเจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคล (DPO)',
    'ผลกระทบหากไม่ให้ความยินยอม เช่น ไม่สามารถใช้งาน Televice Business ได้',
    'สิทธิ์การถอนความยินยอมและขั้นตอนการแจ้ง Televice',
    'การอัปเดตนโยบายและการแจ้งให้ทราบเมื่อมีการเปลี่ยนแปลงสำคัญ',
];
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 uppercase tracking-wide">ขั้นตอนที่ 1 <span class="font-semibold text-slate-800">ยอมรับเงื่อนไข PDPA</span></div>
        <div class="h-2 bg-slate-200 rounded-full mt-2">
            <div class="h-full bg-blue-600 rounded-full w-1/6"></div>
        </div>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">นโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA)</h2>
    <p class="mt-3 text-slate-600">กรุณาอ่านและยืนยันความยินยอมเพื่อดำเนินการยืนยันตัวตนกับ Televice Business Verification e-KYC</p>
    <div class="mt-6 space-y-3">
        <?php foreach ($pdpaItems as $index => $item): ?>
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-700 text-white font-semibold"><?= $index + 1 ?></span>
                    <p class="text-slate-700 text-sm leading-relaxed"><?= htmlspecialchars($item) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <form action="/consent" method="post" class="mt-8 space-y-4">
        <input type="hidden" name="idempotency_key" value="<?= bin2hex(random_bytes(16)) ?>">
        <div>
            <label class="block text-sm font-medium text-slate-700">อีเมลที่ใช้ใน Televice Business Portal (ถ้ามี)</label>
            <input type="email" name="email" class="mt-1 w-full border rounded-lg px-4 py-2" placeholder="example@televice.co.th">
        </div>
        <p class="text-xs text-slate-500">โดยการกดยอมรับ ถือว่าท่านตกลงให้ Televice จัดเก็บและประมวลผลข้อมูลตามรายละเอียดข้างต้น</p>
        <button type="submit" class="w-full md:w-auto bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">ยอมรับและดำเนินการต่อ</button>
    </form>
</div>
HTML;
include __DIR__ . '/layout.php';
