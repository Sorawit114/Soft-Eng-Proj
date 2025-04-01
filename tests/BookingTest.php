<?php

use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
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

    public function testTicketBooking()
    {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, ticket_id, status) VALUES (?, ?, ?)");
        $stmt->execute([1, 1001, 'pending']);

        $stmt = $this->pdo->prepare("SELECT status FROM bookings WHERE user_id = ? AND ticket_id = ?");
        $stmt->execute([1, 1001]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('pending', $result['status']);
    }

    public function testCancelBooking()
    {
        $stmt = $this->pdo->prepare("UPDATE bookings SET status = ? WHERE user_id = ? AND ticket_id = ?");
        $stmt->execute(['cancelled', 1, 1001]);

        $stmt = $this->pdo->prepare("SELECT status FROM bookings WHERE user_id = ? AND ticket_id = ?");
        $stmt->execute([1, 1001]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('cancelled', $result['status']);
    }
}
