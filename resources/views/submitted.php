<?php
$slot = <<<HTML
<div class="bg-white rounded-xl shadow-lg p-8 text-center space-y-4">
    <div class="text-emerald-600 text-4xl">✔</div>
    <h2 class="text-2xl font-bold text-slate-800">ส่งคำขอเรียบร้อยแล้ว</h2>
    <p class="text-slate-600">หมายเลขคำขอของคุณคือ <span class="font-semibold text-slate-800">{$reference}</span></p>
    <p class="text-slate-500 text-sm">{$sla}</p>
    <div class="flex flex-col md:flex-row gap-4 justify-center">
        <a href="/status" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800">ติดตามสถานะ</a>
        <a href="/" class="border border-blue-700 text-blue-700 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50">กลับหน้าแรก</a>
    </div>
</div>
HTML;
include __DIR__ . '/layout.php';
