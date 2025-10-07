<?php
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="border border-red-500 rounded-lg p-6 bg-red-50 mb-6">
        <h2 class="text-xl font-semibold text-red-700 mb-2">ประกาศตามพระราชบัญญัติว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์ พ.ศ.2550 มาตรา 26</h2>
        <p class="text-sm text-red-800">ผู้ใช้งาน Televice Business ต้องยืนยันตัวตนภายใน 7 วัน เพื่อหลีกเลี่ยงการระงับบริการชั่วคราว.</p>
        <a class="text-blue-700 underline" href="https://televice.s3.ap-southeast-7.amazonaws.com/files/2023/10/26182337/computer-crime-act-2550_1.pdf" target="_blank">ดาวน์โหลดรายละเอียดกฎหมาย (PDF)</a>
    </div>
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">ขั้นตอนการยืนยันตัวตน</h3>
            <ol class="list-decimal list-inside text-slate-600 space-y-2 mt-3">
                <li>ยอมรับเงื่อนไขและ PDPA</li>
                <li>กรอกอีเมลและเบอร์โทรที่ใช้ใน Televice Business Portal</li>
                <li>เลือกประเภทผู้ใช้และอัปโหลดเอกสาร</li>
                <li>ตรวจสอบข้อมูลและเซ็นอิเล็กทรอนิกส์</li>
                <li>ส่งคำขอและติดตามสถานะได้ตลอดเวลา</li>
            </ol>
        </div>
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-slate-700">ระบบรองรับการอัปโหลดไฟล์ JPG/PNG/PDF และทำลายน้ำอัตโนมัติทุกไฟล์ ด้วยข้อความ "ใช้สำหรับสมัครใช้บริการกับเทเลไวซ์ บิสิเนส เท่านั้น"</p>
            <p class="text-slate-700 mt-2">ไม่มี OTP / ไม่มีสแกนใบหน้า — ใช้เฉพาะเอกสารและลายเซ็นอิเล็กทรอนิกส์.</p>
        </div>
    </div>
    <div class="mt-8 flex flex-col md:flex-row gap-4">
        <a href="/consent" class="inline-flex items-center justify-center bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">เริ่มยืนยันตัวตน</a>
        <a href="/status" class="inline-flex items-center justify-center bg-white border border-blue-700 text-blue-700 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50">ติดตามสถานะคำขอ</a>
    </div>
</div>
HTML;
include __DIR__ . '/layout.php';
