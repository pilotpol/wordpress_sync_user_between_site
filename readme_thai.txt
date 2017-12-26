[คำแนะนำในการใช้งาน]
==============
1. copy ส่วน #DEFINE_SYNC ในไฟล์ test_connect.php ไปวางใน wp-config.php หลังจากแถวของ $table_prefix

2. copy code ส่วน #ACTION_SYNC_USER_BETWEEN_SITE ในไฟล์ test_connect.php ไปวางใน functions.php ของธีมที่ใช้งานอยู่

3. นำไฟล์ setuser_api.php ไปวางไว้ใน {site}/wp-admin/ ของ site ที่จะรับค่า

[การทำงาน]
=========

{site_1 : (copy define ต่างๆ ตาม 1 และ 2)} ---> {site_2 : (วางไฟล์ setuser_api.php ใน /wp-admin)}

- จะมี site หนึ่งเป็น main เพื่อส่งค่าไปหาอีก site หนึ่ง ตั้งค่าตัวแปรที่ define ในข้อ 1. ให้ตรงกับ host และค่าต่างๆ ที่ใช้งาน
-หากต้องการให้ทั้ง 2 site sync กัน ก็วางไฟล์ และตั้งค่าตาม [คำแนะนำในการใช้งาน] ทั้งสอง site