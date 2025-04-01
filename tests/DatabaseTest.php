<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=aquarium', 'root', '');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function tearDown(): void
    {
        $this->pdo = null;
    }

    public function testDatabaseConnection()
    {
        $this->assertNotNull($this->pdo);
    }

    public function testInsertUser()
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, position) VALUES (?, ?, ?, ?)");
        $stmt->execute(['secureUser', 'secure@example.com', password_hash('securePass', PASSWORD_BCRYPT), 'admin']);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['secure@example.com']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('secureUser', $result['username']);
        $this->assertTrue(password_verify('securePass', $result['password']));
    }

    public function testUpdateUser()
    {
        $this->pdo->prepare("UPDATE users SET username = ? WHERE email = ?")
            ->execute(['updatedUser', 'secure@example.com']);

        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE email = ?");
        $stmt->execute(['secure@example.com']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('updatedUser', $result['username']);
    }

    public function testFetchAllUsers()
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, position) VALUES (?, ?, ?, ?)");
        $stmt->execute(['testUser', 'test@example.com', password_hash('testPass', PASSWORD_BCRYPT), 'user']);

        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertGreaterThan(0, $result['total']);

        $stmt = $this->pdo->prepare("DELETE FROM users WHERE email = ?");
        $stmt->execute(['test@example.com']);
    }

    public function testDeleteUser()
    {
        $this->pdo->prepare("DELETE FROM users WHERE email = ?")
            ->execute(['secure@example.com']);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['secure@example.com']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($result);
    }

    public function testDuplicateEmailInsertion()
    {
        $this->expectException(PDOException::class);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, position) VALUES (?, ?, ?, ?)");
        $stmt->execute(['dupUser', 'duplicate@example.com', 'dupPass', 'user']);
        $stmt->execute(['dupUser2', 'duplicate@example.com', 'dupPass2', 'user']);
    }

    public function testInvalidInsert()
    {
        $this->expectException(PDOException::class);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, position) VALUES (?, ?, ?, ?)");
        $stmt->execute(['short', 'invalid-email', '123', 'user']);
    }
}
