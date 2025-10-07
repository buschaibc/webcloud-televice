# Televice Business Verification e-KYC

ระบบยืนยันตัวตนสำหรับ Televice Business เน้นการตรวจสอบด้วยเอกสารและลายมือชื่ออิเล็กทรอนิกส์ รองรับผู้ใช้ 3 ประเภท (บุคคลธรรมดา, นิติบุคคล, หน่วยงานรัฐ) พร้อมแดชบอร์ดสำหรับผู้ดูแลที่เข้าผ่าน URL `/cpmn` เท่านั้น

## โครงสร้างหลัก
- `public/` — จุดเริ่มต้นของเว็บ (front controller)
- `src/` — โค้ด PHP แยกตามชั้น (Controllers, Services, Repositories, Support)
- `resources/views/` — ไฟล์มุมมอง (Tailwind CSS ผ่าน CDN)
- `sql/schema.sql` — โครงสร้างฐานข้อมูล MariaDB/MySQL
- `storage/` — เก็บไฟล์เอกสาร (secure = เข้ารหัส, public = ไฟล์ลายน้ำ)

## การติดตั้ง
1. ติดตั้ง dependencies ผ่าน Composer
   ```bash
   composer install
   ```
2. สร้างฐานข้อมูลและนำเข้า schema
   ```bash
   mysql -u root -p televice < sql/schema.sql
   ```
3. กำหนด environment (เช่น `.env`) ให้สอดคล้องกับ `config.php`
4. เปิดเซิร์ฟเวอร์ PHP
   ```bash
   php -S 0.0.0.0:8000 -t public
   ```
5. เข้าถึงระบบฝั่งผู้ใช้ที่ `http://localhost:8000/` และฝั่งผู้ดูแลที่ `http://localhost:8000/cpmn`

## คุณสมบัติเด่น
- ลำดับขั้นตอนพร้อม Stepper และ PDPA Consent 15 ข้อ (บันทึก IP/Agent/hash)
- ตรวจสอบสิทธิ์อีเมล/เบอร์โทร ป้องกันการยื่นซ้ำ + แจ้งจำนวนบัญชีที่ใช้เบอร์เดียวกัน
- อัปโหลดเอกสาร JPG/PNG/PDF พร้อมทำลายน้ำอัตโนมัติ เก็บต้นฉบับแบบเข้ารหัส AES-256-CBC
- หน้าสรุปพร้อม Signature Pad และ SLA ชัดเจน (ก่อน/หลัง 16:00 น.)
- ติดตามสถานะด้วยอีเมล+เบอร์ พร้อมไทม์ไลน์สถานะ
- แอดมินจัดการคำขอ, ขอเอกสารเพิ่ม, ปรับสถานะ, Soft/Hard delete, บันทึก Audit & Email Log
- Switch ระบบอีเมลทั่วระบบ หากปิดจะบันทึก log เป็น `skipped_system_disabled`
- บันทึก Traffic ≥ 90 วัน ตามมาตรา 26

## บัญชีผู้ดูแล
- Username: `televice`
- Password: `Admin1234`
> **ควรเปลี่ยนรหัสผ่านทันทีหลังเข้าใช้งานครั้งแรก** (หน้า Settings)

## หมายเหตุ
- ต้องติดตั้ง PHP 8.2+, ส่วนขยาย `imagick` (ถ้ามี) และ `ext-gd`, `ext-mbstring`
- ระบบอีเมลใช้ SMTP ผ่าน PHPMailer สามารถกำหนดค่าใน environment (ดู `config.php`)
- เอกสารจะถูกเก็บ 2 เวอร์ชัน: `storage/secure` (เข้ารหัส) และ `storage/public` (ลายน้ำ)
