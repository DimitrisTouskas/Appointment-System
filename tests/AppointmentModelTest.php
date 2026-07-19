<?php
use PHPUnit\Framework\TestCase;
use App\Core\Database;
use App\Models\Appointment;

class AppointmentModelTest extends TestCase
{
    public function testCreateAppointmentInsertsRow(){
        $db = new Database();
        $connection = $db->connect();
        $appointment = new Appointment($connection);

        $result = $appointment->createAppointment('2030-01-01', '10:00', 'test note', 1);

        $this->assertTrue($result);
    }

    public function testFindByIdReturnsNullForWrongUser(){
        $db = new Database();
        $connection = $db->connect();
        $appointment = new Appointment($connection);

        $appointment->createAppointment('2030-01-01', '10:00', 'idor test', 1);
        $newId = $connection->insert_id;

        $result = $appointment->findById($newId, 2);

        $this->assertNull($result);
    }
}