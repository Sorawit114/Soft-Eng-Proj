<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $pdo;

    // เชื่อมต่อฐานข้อมูลก่อนทำการทดสอบ
    protected function setUp(): void
    {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=aquarium', 'root', 'root');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // สร้างตาราง tickets หากยังไม่มี
        $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS tickets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL
        )
    ");
    }


    // ทดสอบการเชื่อมต่อฐานข้อมูล
    public function testDatabaseConnection()
    {
        $this->assertNotNull($this->pdo);
    }

    // ทดสอบการเพิ่มข้อมูลลงในฐานข้อมูล
    public function testInsertIntoDatabase()
    {
        $stmt = $this->pdo->prepare("INSERT INTO tickets (name, price) VALUES ('Test Ticket', 100)");
        $stmt->execute();

        // ตรวจสอบว่าแถวใหม่ถูกเพิ่มลงในฐานข้อมูล
        $stmt = $this->pdo->query("SELECT * FROM tickets WHERE name = 'Test Ticket'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Test Ticket', $result['name']);
        $this->assertEquals(100, $result['price']);
    }

    // ทดสอบการลบข้อมูลจากฐานข้อมูล
    public function testDeleteFromDatabase()
    {
        // ลบข้อมูลที่เราเพิ่มใน testInsertIntoDatabase
        $this->pdo->query("DELETE FROM tickets WHERE name = 'Test Ticket'");

        $stmt = $this->pdo->query("SELECT * FROM tickets WHERE name = 'Test Ticket'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($result);  // ควรจะไม่มีแถวนี้ในฐานข้อมูลแล้ว
    }
}
