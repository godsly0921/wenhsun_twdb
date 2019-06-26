<?php

declare(strict_types=1);

use Wenhsun\Leave\Application\LeaveApplyCommand;
use Wenhsun\Leave\Application\LeaveApplyService;
use Wenhsun\Leave\Infra\MySQLLeaveApplyRepository;

class LeaveController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {

    }

    public function actionNew()
    {
        $this->render('new', ['documentTypes' => '']);
    }

    public function actionApply()
    {
        $employeeId = $_SESSION['uid'];

        $leaveApplyCommand = new LeaveApplyCommand();
        $leaveApplyCommand->employeeId = $employeeId;
        $leaveApplyCommand->type = $_POST['leave_type'];
        $leaveApplyCommand->startDate = "{$_POST['start_date']} {$_POST['start_time']}";
        $leaveApplyCommand->endDate = "{$_POST['end_date']} {$_POST['end_time']}";
        $leaveApplyCommand->memo = $_POST['memo'];

        $leaveApplyRepository = new MySQLLeaveApplyRepository();
        $leaveApplyServ = new LeaveApplyService($leaveApplyRepository);
        $leaveApplyServ->applyLeave($leaveApplyCommand);

        echo "OK";
    }
}