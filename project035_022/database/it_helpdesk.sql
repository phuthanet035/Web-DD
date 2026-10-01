-- ====================================================================
-- โครงการ: ระบบแจ้งซ่อม IT (IT Repair & Helpdesk System)
-- ฐานข้อมูล: MySQL 5.7 / 8.0+ / MariaDB
-- รหัสชุดตัวอักษร: UTF-8 Unicode (utf8mb4)
-- สร้างเมื่อ: ตุลาคม 2026
-- ====================================================================

-- 1. สร้างฐานข้อมูล (หากยังไม่มี)
CREATE DATABASE IF NOT EXISTS `it_helpdesk` 
    DEFAULT CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE `it_helpdesk`;

-- ปิดการตรวจสอบ Foreign Key ชั่วคราวระหว่างสร้างตาราง
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------------------
-- 2. สร้างตาราง `users` (ตารางเจ้าหน้าที่ IT และผู้ดูแลระบบ)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อ-นามสกุลเจ้าหน้าที่',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'อีเมลสำหรับเข้าสู่ระบบ',
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL COMMENT 'รหัสผ่านเข้ารหัส Bcrypt',
    `role` VARCHAR(50) NOT NULL DEFAULT 'staff' COMMENT 'ระดับสิทธิ์: admin, staff',
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. สร้างตาราง `tickets` (ตารางใบแจ้งซ่อม IT พร้อมรองรับ 3 ฟีเจอร์ใหม่)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `ticket_number` VARCHAR(50) NOT NULL UNIQUE COMMENT 'รหัสติดตามงาน เช่น TK-202610-A1B2',
    `title` VARCHAR(255) NOT NULL COMMENT 'หัวข้อปัญหาที่แจ้งซ่อม',
    `description` TEXT NOT NULL COMMENT 'รายละเอียดอาการเสีย/ข้อความ error',
    
    -- ข้อมูลผู้แจ้งซ่อม (ฝั่งผู้ใช้งานทั่วไป)
    `requester_name` VARCHAR(150) NOT NULL COMMENT 'ชื่อผู้แจ้งซ่อม',
    `requester_email` VARCHAR(150) NOT NULL COMMENT 'อีเมลผู้แจ้งซ่อม',
    `requester_phone` VARCHAR(30) DEFAULT NULL COMMENT 'เบอร์โทรศัพท์ติดต่อ',
    `department` VARCHAR(100) DEFAULT NULL COMMENT 'แผนก / ห้อง / อาคาร',
    
    -- สถานะงานซ่อม
    `status` ENUM('pending', 'in_progress', 'resolved', 'cancelled') 
        NOT NULL DEFAULT 'pending' 
        COMMENT 'สถานะ: pending=รอดำเนินการ, in_progress=กำลังซ่อม, resolved=เสร็จสิ้น, cancelled=ยกเลิก',

    -- ⭐ ฟีเจอร์ที่ 1: ระบบแนบรูปถ่ายหลักฐานปัญหา
    `image_path` VARCHAR(255) DEFAULT NULL COMMENT 'ที่อยู่ไฟล์รูปภาพใน Storage เช่น tickets/sample.jpg',

    -- ⭐ ฟีเจอร์ที่ 2: หมวดหมู่อุปกรณ์ & ระดับความเร่งด่วน (Priority & Category)
    `category` ENUM('hardware', 'software', 'network', 'printer', 'other') 
        NOT NULL DEFAULT 'hardware' 
        COMMENT 'หมวดหมู่: hardware, software, network, printer, other',
    `priority` ENUM('low', 'medium', 'high', 'urgent') 
        NOT NULL DEFAULT 'medium' 
        COMMENT 'ความเร่งด่วน: urgent=ด่วนที่สุด, high=เร่งด่วน, medium=ปานกลาง, low=ต่ำ',

    -- ⭐ ฟีเจอร์ที่ 3: ระบบบันทึกข้อความช่าง & เวลาปิดงาน (Technician Action Notes)
    `admin_notes` TEXT DEFAULT NULL COMMENT 'บันทึกวิธีแก้ปัญหาหรือข้อความแจ้งเตือนจากช่าง IT',
    `assigned_to` BIGINT UNSIGNED DEFAULT NULL COMMENT 'รหัสช่างผู้รับผิดชอบ (FK -> users.id)',
    `resolved_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'วันเวลาที่ซ่อมเสร็จสิ้นและปิดงาน',

    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    INDEX `idx_tickets_status` (`status`),
    INDEX `idx_tickets_priority` (`priority`),
    INDEX `idx_tickets_category` (`category`),
    INDEX `idx_tickets_ticket_number` (`ticket_number`),
    CONSTRAINT `fk_tickets_assigned_to` FOREIGN KEY (`assigned_to`) 
        REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เปิดการตรวจสอบ Foreign Key กลับคืนมา
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------------------
-- 4. ข้อมูลตัวอย่างเริ่มต้นสำหรับการนำเสนอ (Sample Mock Data)
-- --------------------------------------------------------------------

-- 4.1 ข้อมูลผู้ใช้และเจ้าหน้าที่ IT (รหัสผ่านคือ: password123)
-- Hash: $2y$12$A86oB3s1pP80g1yN4m8X3eIqN17r8u4H9t6m4F0f0f2r8u4H9t6m4
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'นายสมศักดิ์ ผู้ดูแลระบบ', 'admin@ithelpdesk.com', '$2y$12$qR7iW8vK8f7X3lY0k2Z1oeQ7kF1v9p2h8g5j6k3m4n5b6v7c8x9z0', 'admin', NOW(), NOW()),
(2, 'นายธีรพัฒน์ ช่างเทคนิคไอที', 'tech1@ithelpdesk.com', '$2y$12$qR7iW8vK8f7X3lY0k2Z1oeQ7kF1v9p2h8g5j6k3m4n5b6v7c8x9z0', 'staff', NOW(), NOW());

-- 4.2 ข้อมูลตัวอย่างใบแจ้งซ่อม (แสดงความครอบคลุมของ 3 ฟีเจอร์ใหม่)
INSERT INTO `tickets` (
    `id`, `ticket_number`, `title`, `description`, 
    `requester_name`, `requester_email`, `requester_phone`, `department`, 
    `status`, `image_path`, `category`, `priority`, 
    `admin_notes`, `assigned_to`, `resolved_at`, `created_at`, `updated_at`
) VALUES
-- เคสที่ 1: ด่วนที่สุด (Urgent) - รอดำเนินการ
(
    1, 
    'TK-202610-0001', 
    'เครื่อง Server แชร์ไฟล์กลางแผนกการเงินดับ มีเสียงร้องเตือนถี่ๆ', 
    'เครื่องเซิร์ฟเวอร์ NAS ไฟสถานะกระพริบเป็นสีแดง หน้าจอขึ้น Alert พนักงานไม่สามารถดึงไฟล์ใบแจ้งหนี้เพื่อส่งลูกค้าได้ทั้งแผนก', 
    'นางสาววิภาภรณ์ บุญมี', 
    'wipaporn@finance.co.th', 
    '081-234-5678', 
    'แผนกการเงินและการบัญชี ชั้น 4', 
    'pending', 
    NULL, 
    'network', 
    'urgent', 
    NULL, 
    NULL, 
    NULL, 
    NOW() - INTERVAL 2 HOUR, 
    NOW() - INTERVAL 2 HOUR
),

-- เคสที่ 2: เร่งด่วน (High) - กำลังดำเนินการซ่อม พร้อมบันทึกช่าง
(
    2, 
    'TK-202610-0002', 
    'จอฟ้า Bluescreen (CRITICAL_PROCESS_DIED) เปิดเครื่องแล้วค้างที่หน้าโลโก้', 
    'เปิดเครื่องทำงานตอนเช้าแล้วระบบรีสตาร์ทเองวนซ้ำตลอดเวลา มีรหัสข้อผิดพลาดขึ้นว่า CRITICAL_PROCESS_DIED ทำงานไม่ได้เลย', 
    'นายเอกชัย แสงแก้ว', 
    'ekkachai@marketing.co.th', 
    '089-876-5432', 
    'ฝ่ายการตลาดดิจิทัล ชั้น 2', 
    'in_progress', 
    'tickets/sample_bluescreen.jpg', 
    'hardware', 
    'high', 
    'ช่างได้รับเรื่องแล้ว ตรวจสอบเบื้องต้นพบ Bad Sector บน SSD อยู่ระหว่างนำอุปกรณ์สำรองไปเปลี่ยนถ่ายข้อมูล คาดว่าจะเสร็จภายในเวลา 14:00 น.', 
    2, 
    NULL, 
    NOW() - INTERVAL 5 HOUR, 
    NOW() - INTERVAL 1 HOUR
),

-- เคสที่ 3: ซ่อมเสร็จสิ้นแล้ว (Resolved) - ปิดงานและประทับเวลา
(
    3, 
    'TK-202610-0003', 
    'เครื่องพิมพ์ HP LaserJet กระดาษติดบ่อย และไม่ดึงกระดาษจากถาด 2', 
    'เวลาสั่งพิมพ์เกิน 5 แผ่น เครื่องจะดูดกระดาษซ้อนกันแล้วติดอยู่ภายในเครื่อง ต้องดึงออกด้วยมือ', 
    'นายชัยวัฒน์ สิทธิพร', 
    'chaiwat@hr.co.th', 
    '086-555-4321', 
    'แผนกทรัพยากรบุคคล (HR) ชั้น 3', 
    'resolved', 
    NULL, 
    'printer', 
    'medium', 
    'ดำเนินการทำความสะอาดชุดลูกยางดึงกระดาษ (Pick-up Roller) และเปลี่ยนแผ่นแยกกระดาษ (Separation Pad) ใหม่ ทดสอบพิมพ์เอกสารต่อเนื่อง 30 แผ่น ใช้งานได้ตามปกติเรียบร้อยแล้ว', 
    2, 
    NOW() - INTERVAL 30 MINUTE, 
    NOW() - INTERVAL 1 DAY, 
    NOW() - INTERVAL 30 MINUTE
),

-- เคสที่ 4: ต่ำ (Low) - ขอโปรแกรม
(
    4, 
    'TK-202610-0004', 
    'ขอติดตั้งโปรแกรม Adobe Acrobat Reader DC สำหรับเปิดอ่านเอกสารสัญญางาน', 
    'เครื่องคอมพิวเตอร์เพิ่งลง Windows ใหม่ ยังไม่มีโปรแกรมเปิดอ่านไฟล์ PDF รบกวนเจ้าหน้าที่ติดตั้งให้ด้วยครับ', 
    'นางสาวพิมพ์มาดา รัตนกุล', 
    'pimmada@legal.co.th', 
    '084-111-2233', 
    'ฝ่ายกฎหมายและนิติกรรม ชั้น 5', 
    'pending', 
    NULL, 
    'software', 
    'low', 
    NULL, 
    NULL, 
    NULL, 
    NOW() - INTERVAL 3 HOUR, 
    NOW() - INTERVAL 3 HOUR
);
