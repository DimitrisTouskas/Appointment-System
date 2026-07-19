<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\AppointmentController;

class AppointmentControllerTest extends TestCase{
    public function testCreateRejectsPastDate(){
        $_SESSION['User_id'] = 1;
        $falsecase =  new AppointmentController('2020-01-01', '10:00', 'test', null);
        $result = $falsecase ->create();
        $this->assertEquals(400, $result['code']);
    }


    public function testCreateRejectsPastTimeSameDay(){
        $_SESSION['User_id'] = 1;
        $falsecreateFunctionCase =  new AppointmentController(date('Y-m-d'), '00:01', 'test', null);
        $result = $falsecreateFunctionCase ->create();
        $this->assertEquals(400, $result['code']);


    }

    public function testUpdateRejectsPastDate(){
         $_SESSION['User_id'] = 1;
        $falsecase =  new AppointmentController('2020-01-01', '10:00', 'test', null);
        $result = $falsecase ->update(1);
        $this->assertEquals(400, $result['code']);
    }

    public function testUpdateStatusRejectsInvalidStatus(){
        $_SESSION['User_id'] = 1;
        $falsecreateFunctionCase =  new AppointmentController();
        $result = $falsecreateFunctionCase ->updateStatus(1, 'banana');
        $this->assertEquals(400, $result['code']);

    }

    public function testDeleteRejectsEmptyId(){
        $_SESSION['User_id'] = 1;
        $falsecreateFunctionCase =  new AppointmentController();
        $result = $falsecreateFunctionCase ->delete("");
        $this->assertEquals(400, $result['code']);
    }
}
?>