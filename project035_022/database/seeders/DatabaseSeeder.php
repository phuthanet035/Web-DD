<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * สร้างข้อมูลจำลองสำหรับนำเสนอระบบแจ้งซ่อม IT
     */
    public function run(): void
    {
        // 1. สร้างบัญชีเจ้าหน้าที่ IT / Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ithelpdesk.com'],
            [
                'name' => 'นายสมศักดิ์ ผู้ดูแลระบบ',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $tech1 = User::firstOrCreate(
            ['email' => 'tech1@ithelpdesk.com'],
            [
                'name' => 'นายธีรพัฒน์ ช่างเทคนิคไอที',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        // 2. สร้างข้อมูลตัวอย่าง Ticket จำลอง 4 สถานการณ์
        $tickets = [
            [
                'ticket_number' => 'TK-202610-0001',
                'title' => 'เครื่อง Server แชร์ไฟล์กลางแผนกการเงินดับ มีเสียงร้องเตือนถี่ๆ',
                'description' => 'เครื่องเซิร์ฟเวอร์ NAS ไฟสถานะกระพริบเป็นสีแดง หน้าจอขึ้น Alert พนักงานไม่สามารถดึงไฟล์ใบแจ้งหนี้เพื่อส่งลูกค้าได้ทั้งแผนก',
                'requester_name' => 'นางสาววิภาภรณ์ บุญมี',
                'requester_email' => 'wipaporn@finance.co.th',
                'requester_phone' => '081-234-5678',
                'department' => 'แผนกการเงินและการบัญชี ชั้น 4',
                'status' => 'pending',
                'image_path' => null,
                'category' => 'network',
                'priority' => 'urgent',
                'admin_notes' => null,
                'assigned_to' => null,
                'resolved_at' => null,
            ],
            [
                'ticket_number' => 'TK-202610-0002',
                'title' => 'จอฟ้า Bluescreen (CRITICAL_PROCESS_DIED) เปิดเครื่องแล้วค้างที่หน้าโลโก้',
                'description' => 'เปิดเครื่องทำงานตอนเช้าแล้วระบบรีสตาร์ทเองวนซ้ำตลอดเวลา มีรหัสข้อผิดพลาดขึ้นว่า CRITICAL_PROCESS_DIED ทำงานไม่ได้เลย',
                'requester_name' => 'นายเอกชัย แสงแก้ว',
                'requester_email' => 'ekkachai@marketing.co.th',
                'requester_phone' => '089-876-5432',
                'department' => 'ฝ่ายการตลาดดิจิทัล ชั้น 2',
                'status' => 'in_progress',
                'image_path' => 'tickets/sample_bluescreen.jpg',
                'category' => 'hardware',
                'priority' => 'high',
                'admin_notes' => 'ช่างได้รับเรื่องแล้ว ตรวจสอบเบื้องต้นพบ Bad Sector บน SSD อยู่ระหว่างนำอุปกรณ์สำรองไปเปลี่ยนถ่ายข้อมูล คาดว่าจะเสร็จภายในเวลา 14:00 น.',
                'assigned_to' => $tech1->id,
                'resolved_at' => null,
            ],
            [
                'ticket_number' => 'TK-202610-0003',
                'title' => 'เครื่องพิมพ์ HP LaserJet กระดาษติดบ่อย และไม่ดึงกระดาษจากถาด 2',
                'description' => 'เวลาสั่งพิมพ์เกิน 5 แผ่น เครื่องจะดูดกระดาษซ้อนกันแล้วติดอยู่ภายในเครื่อง ต้องดึงออกด้วยมือ',
                'requester_name' => 'นายชัยวัฒน์ สิทธิพร',
                'requester_email' => 'chaiwat@hr.co.th',
                'requester_phone' => '086-555-4321',
                'department' => 'แผนกทรัพยากรบุคคล (HR) ชั้น 3',
                'status' => 'resolved',
                'image_path' => null,
                'category' => 'printer',
                'priority' => 'medium',
                'admin_notes' => 'ดำเนินการทำความสะอาดชุดลูกยางดึงกระดาษ (Pick-up Roller) และเปลี่ยนแผ่นแยกกระดาษ (Separation Pad) ใหม่ ทดสอบพิมพ์เอกสารต่อเนื่อง 30 แผ่น ใช้งานได้ตามปกติเรียบร้อยแล้ว',
                'assigned_to' => $tech1->id,
                'resolved_at' => now()->subMinutes(30),
            ],
            [
                'ticket_number' => 'TK-202610-0004',
                'title' => 'ขอติดตั้งโปรแกรม Adobe Acrobat Reader DC สำหรับเปิดอ่านเอกสารสัญญางาน',
                'description' => 'เครื่องคอมพิวเตอร์เพิ่งลง Windows ใหม่ ยังไม่มีโปรแกรมเปิดอ่านไฟล์ PDF รบกวนเจ้าหน้าที่ติดตั้งให้ด้วยครับ',
                'requester_name' => 'นางสาวพิมพ์มาดา รัตนกุล',
                'requester_email' => 'pimmada@legal.co.th',
                'requester_phone' => '084-111-2233',
                'department' => 'ฝ่ายกฎหมายและนิติกรรม ชั้น 5',
                'status' => 'pending',
                'image_path' => null,
                'category' => 'software',
                'priority' => 'low',
                'admin_notes' => null,
                'assigned_to' => null,
                'resolved_at' => null,
            ],
        ];

        foreach ($tickets as $item) {
            Ticket::updateOrCreate(['ticket_number' => $item['ticket_number']], $item);
        }
    }
}
