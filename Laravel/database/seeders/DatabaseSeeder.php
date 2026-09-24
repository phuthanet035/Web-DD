<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\RepairTicket;
use App\Models\TicketHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin & Technician Accounts
        $admin = User::firstOrCreate(
            ['email' => 'admin@it.com'],
            [
                'name' => 'IT Admin (ผู้ดูแลระบบ)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081-111-2233',
                'department' => 'IT Department',
            ]
        );

        $tech1 = User::firstOrCreate(
            ['email' => 'tech@it.com'],
            [
                'name' => 'สมชาย ช่างไอที',
                'password' => Hash::make('password123'),
                'role' => 'technician',
                'phone' => '082-333-4455',
                'department' => 'IT Support',
            ]
        );

        $tech2 = User::firstOrCreate(
            ['email' => 'tech2@it.com'],
            [
                'name' => 'วิชัย ช่างเน็ตเวิร์ก',
                'password' => Hash::make('password123'),
                'role' => 'technician',
                'phone' => '083-555-6677',
                'department' => 'Network & Infrastructure',
            ]
        );

        // 2. Categories
        $categories = [
            [
                'name' => 'คอมพิวเตอร์ / โน้ตบุ๊ก',
                'icon' => 'laptop',
                'description' => 'เครื่องเปิดไม่ติด, จอฟ้า, เครื่องช้าผิดปกติ, ฮาร์ดดิสก์เต็ม, คีย์บอร์ด/เมาส์เสีย',
            ],
            [
                'name' => 'ปริ้นเตอร์ & สแกนเนอร์',
                'icon' => 'printer',
                'description' => 'กระดาษติด, หมึกหมด, พิมพ์งานไม่ออก, เชื่อมต่อเครื่องพิมพ์ไม่ได้',
            ],
            [
                'name' => 'ระบบเครือข่าย & อินเทอร์เน็ต',
                'icon' => 'wifi',
                'description' => 'เข้าเว็บไม่ได้, Wi-Fi หลุดบ่อย, สาย LAN ชำรุด, เชื่อมต่อ VPN ไม่ได้',
            ],
            [
                'name' => 'ซอฟต์แวร์ & โปรแกรมสำนักงาน',
                'icon' => 'terminal',
                'description' => 'โปรแกรมเปิดไม่ขึ้น, Microsoft Office หมดอายุ, ไวรัส/มัลแวร์, Windows อัปเดตค้าง',
            ],
            [
                'name' => 'อีเมล & บัญชีผู้ใช้ / รหัสผ่าน',
                'icon' => 'user-check',
                'description' => 'ลืมรหัสผ่าน, บัญชีถูกล็อก, ส่งอีเมลไม่ไป, ขอสิทธิ์เข้าถึงโฟลเดอร์แชร์',
            ],
            [
                'name' => 'อุปกรณ์ไอทีอื่น ๆ',
                'icon' => 'cpu',
                'description' => 'โปรเจกเตอร์ห้องประชุม, จอภาพเสริม, กล้องวงจรปิด, เครื่องสำรองไฟ (UPS)',
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 3. Sample Tickets
        if (RepairTicket::count() === 0) {
            // Ticket 1: Pending
            $ticket1 = RepairTicket::create([
                'ticket_number' => 'IT-20260924-001',
                'category_id' => $categoryModels[0]->id,
                'title' => 'คอมพิวเตอร์เปิดไม่ติด มีไฟกระพริบสีส้ม',
                'description' => 'กดปุ่มเปิดเครื่องแล้วพัดลมหมุนแป๊บเดียวแล้วดับ มีไฟสีส้มกระพริบที่ปุ่ม Power รบกวนช่วยตรวจสอบด่วนครับ งานเอกสารเร่งด่วน',
                'device_code' => 'PC-ACC-042',
                'location' => 'อาคาร A ชั้น 3 แผนกบัญชีและการเงิน',
                'priority' => 'urgent',
                'status' => 'pending',
                'requester_name' => 'คุณสุดารัตน์ พรประเสริฐ',
                'requester_phone' => '089-123-4567',
                'requester_email' => 'sudarat@company.com',
                'assigned_to' => null,
            ]);

            TicketHistory::create([
                'repair_ticket_id' => $ticket1->id,
                'action' => 'created',
                'comment' => 'ส่งคำขอแจ้งซ่อมเข้าระบบสำเร็จ',
            ]);

            // Ticket 2: In Progress
            $ticket2 = RepairTicket::create([
                'ticket_number' => 'IT-20260924-002',
                'category_id' => $categoryModels[1]->id,
                'title' => 'ปริ้นเตอร์ HP LaserJet ขึ้นแจ้งเตือน Paper Jam ตลอดเวลา',
                'description' => 'ดึงเศษกระดาษออกหมดแล้วแต่เครื่องยังฟ้อง Error 13.00 กระดาษติด สั่งพิมพ์ไม่ได้ทั้งแผนก',
                'device_code' => 'PRT-HR-003',
                'location' => 'อาคาร B ชั้น 2 แผนกทรัพยากรบุคคล (HR)',
                'priority' => 'high',
                'status' => 'in_progress',
                'requester_name' => 'คุณกิตติศักดิ์ ชูใจ',
                'requester_phone' => '086-987-6543',
                'requester_email' => 'kittisak@company.com',
                'assigned_to' => $tech1->id,
            ]);

            TicketHistory::create([
                'repair_ticket_id' => $ticket2->id,
                'action' => 'created',
                'comment' => 'ส่งคำขอแจ้งซ่อมเข้าระบบ',
            ]);
            TicketHistory::create([
                'repair_ticket_id' => $ticket2->id,
                'user_id' => $admin->id,
                'action' => 'assigned',
                'comment' => 'มอบหมายงานให้ช่าง สมชาย ช่างไอที ดำเนินการ',
            ]);
            TicketHistory::create([
                'repair_ticket_id' => $ticket2->id,
                'user_id' => $tech1->id,
                'action' => 'status_changed',
                'comment' => 'รับงานแล้ว กำลังเข้าไปตรวจสอบตัวเซ็นเซอร์ตรวจจับกระดาษ',
            ]);

            // Ticket 3: Resolved
            $ticket3 = RepairTicket::create([
                'ticket_number' => 'IT-20260923-089',
                'category_id' => $categoryModels[2]->id,
                'title' => 'Wi-Fi แผนกการตลาดหลุดบ่อย สัญญาณอ่อนมาก',
                'description' => 'ต่อสัญญาณ AP-MKT-02 ไม่ได้ ขึ้นหลุดและขอรหัสผ่านใหม่ตลอดเวลา',
                'device_code' => 'AP-MKT-02',
                'location' => 'อาคาร A ชั้น 4 แผนกการตลาด',
                'priority' => 'medium',
                'status' => 'resolved',
                'requester_name' => 'คุณปิยะพร แสงดาว',
                'requester_phone' => '084-555-1234',
                'requester_email' => 'piyaporn@company.com',
                'assigned_to' => $tech2->id,
                'resolution_note' => 'ทำการรีบูต Access Point และอัปเดต Firmware ตัวกระจายสัญญาณ ปัจจุบันทดสอบเชื่อมต่อความเร็ว 100Mbps ใช้งานได้ปกติเรียบร้อย',
                'resolved_at' => now()->subDay(),
            ]);

            TicketHistory::create([
                'repair_ticket_id' => $ticket3->id,
                'action' => 'created',
                'comment' => 'ส่งคำขอแจ้งซ่อมเข้าระบบ',
            ]);
            TicketHistory::create([
                'repair_ticket_id' => $ticket3->id,
                'user_id' => $tech2->id,
                'action' => 'resolved',
                'comment' => 'ทำการแก้ไขและทดสอบสัญญาณเรียบร้อย พร้อมปิดงาน',
            ]);
        }
    }
}
