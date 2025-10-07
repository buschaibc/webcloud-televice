<?php
$slot = <<<HTML
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-slate-800">แดชบอร์ดภาพรวม</h2>
        <p class="text-sm text-slate-500">สถานะระบบอีเมล: <span class="font-semibold text-blue-700">{$emailStatus}</span></p>
        <div class="grid md:grid-cols-4 gap-4 mt-6">
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">คำขอทั้งหมด</p>
                <p class="text-2xl font-bold text-slate-800">{$total}</p>
            </div>
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">รอตรวจสอบ</p>
                <p class="text-2xl font-bold text-amber-600">{$pending}</p>
            </div>
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">ขอเอกสารเพิ่ม</p>
                <p class="text-2xl font-bold text-rose-600">{$needMore}</p>
            </div>
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50">
                <p class="text-sm text-slate-500">อนุมัติแล้ว</p>
                <p class="text-2xl font-bold text-emerald-600">{$approved}</p>
            </div>
        </div>
    </div>
</div>
HTML;
include __DIR__ . '/layout.php';
