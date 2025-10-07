<?php
$typeLabels = [
    'individual' => 'บุคคลธรรมดา',
    'corporate' => 'นิติบุคคล',
    'government' => 'หน่วยงานรัฐ/ภาครัฐ',
];
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500 uppercase tracking-wide">ขั้นตอนที่ 5 <span class="font-semibold text-slate-800">ตรวจสอบและลงลายมือชื่อ</span></div>
        <div class="h-2 bg-slate-200 rounded-full mt-2">
            <div class="h-full bg-blue-600 rounded-full w-5/6"></div>
        </div>
    </div>
    <h2 class="text-2xl font-bold text-slate-800">ตรวจสอบข้อมูลและเซ็นยืนยัน</h2>
    <p class="mt-3 text-slate-600">กรุณาตรวจสอบข้อมูลก่อนลงลายมือชื่ออิเล็กทรอนิกส์ ระบบจะแสดงข้อมูลที่จำเป็นแบบปกปิดบางส่วนเพื่อความปลอดภัย</p>
    <div class="mt-6 grid md:grid-cols-2 gap-6">
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <h3 class="text-lg font-semibold text-slate-700">ข้อมูลคำขอ</h3>
            <dl class="mt-3 space-y-2 text-sm text-slate-600">
                <div><dt class="font-semibold">หมายเลขคำขอ</dt><dd><?= htmlspecialchars($reference) ?></dd></div>
                <div><dt class="font-semibold">ประเภทผู้ใช้</dt><dd><?= htmlspecialchars($typeLabels[$type] ?? '') ?></dd></div>
                <div><dt class="font-semibold">อีเมล</dt><dd><?= htmlspecialchars($email) ?></dd></div>
                <div><dt class="font-semibold">เบอร์โทรศัพท์</dt><dd><?= htmlspecialchars($phone) ?></dd></div>
            </dl>
        </div>
        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
            <h3 class="text-lg font-semibold text-slate-700">รายการเอกสาร</h3>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <?php foreach ($documents as $key => $doc): ?>
                    <li class="flex items-center justify-between">
                        <span><?= htmlspecialchars($doc['filename'] ?? $key) ?></span>
                        <span class="text-emerald-600">✔</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <form action="/summary" method="post" class="mt-8 space-y-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700">ลายมือชื่ออิเล็กทรอนิกส์</label>
            <div class="border border-slate-300 rounded-lg bg-white">
                <canvas id="signature-canvas" class="w-full h-48"></canvas>
            </div>
            <input type="hidden" name="signature_data" id="signature_data">
            <div class="flex justify-between mt-2 text-sm text-slate-500">
                <span>ลงลายมือชื่อโดยใช้นิ้วหรือเมาส์</span>
                <button type="button" id="clear-signature" class="text-blue-700 hover:underline">ล้างลายเซ็น</button>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-lg p-4">
            เมื่อกดส่ง ถือว่าท่านรับรองว่าเอกสารและข้อมูลที่ให้มาถูกต้อง และยอมรับ SLA การตรวจสอบภายใน 1 วันทำการ (ส่งหลัง 16:00 น. ตรวจในวันถัดไป)
        </div>
        <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">ส่งคำขอ</button>
    </form>
</div>
<script>
const canvas = document.getElementById('signature-canvas');
const signaturePad = new SignaturePad(canvas, {minWidth: 0.8, maxWidth: 2.5, penColor: '#0f172a'});
function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext('2d').scale(ratio, ratio);
    signaturePad.clear();
}
window.addEventListener('resize', resizeCanvas);
resizeCanvas();
document.querySelector('form').addEventListener('submit', function (e) {
    if (signaturePad.isEmpty()) {
        e.preventDefault();
        alert('กรุณาลงลายมือชื่อก่อนส่ง');
        return false;
    }
    document.getElementById('signature_data').value = signaturePad.toDataURL();
});
document.getElementById('clear-signature').addEventListener('click', function () {
    signaturePad.clear();
});
</script>
HTML;
include __DIR__ . '/layout.php';
