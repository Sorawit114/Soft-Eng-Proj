<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // เปลี่ยนเป็นข้อมูลผู้ใช้ที่เชื่อมต่อกับฐานข้อมูล MySQL ของคุณ
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=aquarium', 'root', ''); // ใช้ root และรหัสผ่านว่าง
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // ✅ ทดสอบการเชื่อมต่อฐานข้อมูล
    public function testDatabaseConnection()
    {
        $this->assertNotNull($this->pdo);
    }

    // ✅ ทดสอบการเพิ่มข้อมูลลงใน users
    public function testInsertUser()
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, position) VALUES ('testUser', 'test@example.com', 'testPass', 'user')");
        $stmt->execute();

        $stmt = $this->pdo->query("SELECT * FROM users WHERE email = 'test@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('testUser', $result['username']);
        $this->assertEquals('test@example.com', $result['email']);
    }

    // ✅ ทดสอบการอัปเดตข้อมูล
    public function testUpdateUser()
    {
        $this->pdo->query("UPDATE users SET username = 'updatedUser' WHERE email = 'test@example.com'");

        $stmt = $this->pdo->query("SELECT * FROM users WHERE email = 'test@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('updatedUser', $result['username']);
    }

    // ✅ ทดสอบการดึงข้อมูลทั้งหมด
    public function testFetchAllUsers()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertGreaterThan(0, $result['total']);
    }

    // ✅ ทดสอบการลบข้อมูล
    public function testDeleteUser()
    {
        $this->pdo->query("DELETE FROM users WHERE email = 'test@example.com'");

        $stmt = $this->pdo->query("SELECT * FROM users WHERE email = 'test@example.com'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($result);
    }
}
