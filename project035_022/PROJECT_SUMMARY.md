# เอกสารสรุปโครงงาน: ระบบแจ้งซ่อม IT (IT Repair & Helpdesk System)
**เฟรมเวิร์ก:** Laravel Framework (PHP MVC)  
**ฐานข้อมูล:** MySQL 5.7 / 8.0+ (utf8mb4)  
**ไฟล์สคริปต์ฐานข้อมูล MySQL (.sql):** [`database/it_helpdesk.sql`](file:///c:/Users/camer/Downloads/project035_022/database/it_helpdesk.sql)  
**ไฟล์เอกสาร PDF สำหรับพูดนำเสนอหน้าห้อง:** [`IT_Helpdesk_System_Presentation.pdf`](file:///c:/Users/camer/Downloads/project035_022/IT_Helpdesk_System_Presentation.pdf)  
**ไฟล์ HTML ต้นฉบับสำหรับพิมพ์ PDF:** [`presentation_docs/presentation.html`](file:///c:/Users/camer/Downloads/project035_022/presentation_docs/presentation.html)

---

## 🗄️ 1. การตั้งค่าและการใช้งานฐานข้อมูล MySQL ในระบบ

### 1.1 ไฟล์การตั้งค่าการเชื่อมต่อ ([.env](file:///c:/Users/camer/Downloads/project035_022/.env))
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_helpdesk
DB_USERNAME=root
DB_PASSWORD=
```

### 1.2 ไฟล์ SQL สคริปต์สำเร็จรูป ([database/it_helpdesk.sql](file:///c:/Users/camer/Downloads/project035_022/database/it_helpdesk.sql))
ประกอบด้วยคำสั่งสร้างฐานข้อมูล `it_helpdesk`, โครงสร้าง 2 ตารางหลัก (`users`, `tickets`), ดัชนี (Indexes), Foreign Key Constraint, และข้อมูลตัวอย่าง (Mock Data) สำหรับนำเสนอหน้าห้อง 4 เคสสมจริง

### 1.3 วิธีการนำเข้าฐานข้อมูล MySQL (ผ่าน phpMyAdmin ใน XAMPP)
1. เปิดโปรแกรม **XAMPP Control Panel** และกดปุ่ม **Start** ที่โมดูล **MySQL**
2. เปิดเบราว์เซอร์แล้วเข้าไปที่ `http://localhost/phpmyadmin`
3. ไปที่แท็บ **Import (นำเข้า)**
4. เลือกไฟล์ [`database/it_helpdesk.sql`](file:///c:/Users/camer/Downloads/project035_022/database/it_helpdesk.sql) แล้วกดปุ่ม **Import (ไป/ตกลง)**
5. ฐานข้อมูล `it_helpdesk` พร้อมตารางและข้อมูลตัวอย่างจะถูกสร้างขึ้นมาทันที

---

## 📊 2. โครงสร้างตารางใน MySQL (Schema Architecture)

### 📋 ตาราง `users` (เจ้าหน้าที่และแอดมิน)
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(255)) - ชื่อเจ้าหน้าที่
- `email` (VARCHAR(255), UNIQUE) - อีเมลเข้าสู่ระบบ
- `password` (VARCHAR(255)) - รหัสผ่านเข้ารหัส Bcrypt
- `role` (VARCHAR(50)) - สิทธิ์การใช้งาน (`admin`, `staff`)
- `timestamps` (`created_at`, `updated_at`)

### 🎫 ตาราง `tickets` (ใบแจ้งซ่อม IT พร้อม 3 ฟีเจอร์ใหม่)
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `ticket_number` (VARCHAR(50), UNIQUE, INDEX) - รหัสตั๋ว เช่น `TK-202610-0001`
- `title` (VARCHAR(255)) - หัวข้อปัญหา
- `description` (TEXT) - อาการเสียอย่างละเอียด
- `requester_name` (VARCHAR(150)) - ชื่อผู้แจ้ง
- `requester_email` (VARCHAR(150)) - อีเมลผู้แจ้ง
- `requester_phone` (VARCHAR(30)) - เบอร์ติดต่อ
- `department` (VARCHAR(100)) - แผนก/ห้อง
- `status` (ENUM: 'pending', 'in_progress', 'resolved', 'cancelled') - สถานะงาน
- **⭐ `image_path` (VARCHAR(255), NULL) - [ฟีเจอร์ 1: แนบรูปหลักฐาน]**
- **⭐ `category` (ENUM: 'hardware', 'software', 'network', 'printer', 'other') - [ฟีเจอร์ 2: หมวดหมู่]**
- **⭐ `priority` (ENUM: 'low', 'medium', 'high', 'urgent') - [ฟีเจอร์ 2: ความด่วน]**
- **⭐ `admin_notes` (TEXT, NULL) - [ฟีเจอร์ 3: บันทึกของช่าง]**
- `assigned_to` (BIGINT UNSIGNED, NULL, Foreign Key -> `users.id`) - ช่างผู้รับผิดชอบ
- **⭐ `resolved_at` (TIMESTAMP, NULL) - [ฟีเจอร์ 3: เวลาปิดงาน]**
- `timestamps` (`created_at`, `updated_at`)

---

## 👥 3. การแบ่งบทบาท 3 ส่วนในระบบ
1. **ส่วนผู้ใช้งานทั่วไป (Public):** ไม่ต้องล็อกอิน แจ้งซ่อม แนบรูป ระบุความด่วน ได้รับรหัส Ticket ID
2. **ส่วนเจ้าหน้าที่ IT (Admin/Staff):** ล็อกอินผ่าน Middleware `auth`, แดชบอร์ดคัดกรองงานด่วน ตรวจสอบภาพ บันทึกผลการซ่อม
3. **ส่วนเชื่อมต่อข้อมูล (Shared Integration):** รหัส Ticket ID, หน้าติดตามสถานะ ([track.blade.php](file:///c:/Users/camer/Downloads/project035_022/resources/views/tickets/track.blade.php)), และตารางข้อมูล `tickets` ใน MySQL
