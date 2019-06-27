<?php

declare(strict_types=1);

use Wenhsun\Leave\Application\EmployeeLeaveService;
use Wenhsun\Leave\Application\LeaveApplyCommand;
use Wenhsun\Leave\Application\LeaveApplyService;
use Employee as EmployeeORM;
use Wenhsun\Leave\Domain\Model\Employee;
use Wenhsun\Leave\Domain\Model\EmployeeId;
use Wenhsun\Leave\Domain\Service\EmployeeLeaveCalculator;
use Wenhsun\Leave\Infra\MySQLLeaveApplyRepository;

class LeaveController extends Controller
{
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $employeeOrmEnt = EmployeeORM::model()->findByPk($_SESSION['uid']);

        if ($employeeOrmEnt === null) {
            //todo
        }

        $employee = new Employee(new EmployeeId($employeeOrmEnt->id), $employeeOrmEnt->create_at);

        $employeeLeaveCalculator = new EmployeeLeaveCalculator();
        $annualLeaveMinutes = $employeeLeaveCalculator->calcAnnualLeaveInRecentYear(new DateTime(), $employee);

        $employeeLeaveServ = new EmployeeLeaveService();
        $annualLeaveUsedMinutes = $employeeLeaveServ->queryEmployeeLeaveSum(new DateTime(), new DateTime(), '');

        $data = [
            'annual' => ($annualLeaveMinutes->minutesValue() - $annualLeaveUsedMinutes->minutesValue()) / 60
        ];

        $this->render('index', ['data' => $data]);
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