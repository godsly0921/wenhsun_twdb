<?php
class AnnualLeaveFiscalYearCommand extends CConsoleCommand
{
    public function run($argv)
    {
        $empService = new EmployeeService();
        $leaveService = new LeaveService();
        foreach($argv as $key => $arrvalue){
            //年度日期 Y-m-d
            if($arrvalue=="-date" )
                $arrpara['date']=$argv[$key+1];
            //員工編號
            if($arrvalue=="-emp" )
                $arrpara['emp']=$argv[$key+1];
            //init
            if($arrvalue=="-init" )
                $arrpara['init']=$argv[$key+1];
            //init
            if($arrvalue=="-close" )
                $arrpara['close']=$argv[$key+1];
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $nowDate = !empty($arrpara['date']) ? new DateTime($arrpara['date']) : new DateTime();
            if(!empty($arrpara['init'])){
                $emp = $empService->findEmployeeInRolesListObject([2,5,26,33]);//列出文訊正職員工狀態為啟用中的
                if($emp){
                    foreach($emp as $key => $value) {
                        $nowDate = new DateTime();
                        $employee = Employee::model()->findByPk($value["id"]);
                        $onBoardDate = new DateTime($employee->onboard_date);
                        $nowDate->setTime(0, 0, 0);
                        $onBoardDate->setTime(0, 0, 0);

                        $Year_Diff = date("Y") - $onBoardDate->format('Y');
                        for ($i=1; $i <= $Year_Diff; $i++) {
                            $onBoardDate = new DateTime($employee->onboard_date);
                            $onBoardDate->setTime(0, 0, 0);
                            $runDate = $onBoardDate->modify('+' . $i . ' year');
                            $runDate = new DateTime($runDate->format('Y').'-01-01'); //yyyy-mm-dd 
                            $leaveService->calcAnnualLeaveSummaryOnBoardDate_FiscalYear($runDate, $employee);
                            if($i == $Year_Diff){
                                $leaveService->SpecialLeaveYearIdInit($runDate->format('Y'), $employee);
                            }
                        }
                    }
                }
            }elseif (!empty($arrpara['close'])) {
                $nowDate = new DateTime();
                $nowDate->setTime(0, 0, 0);
                $nowDate->modify('-1 year');
                $sql = "UPDATE `special_leave_year` SET is_close='1' WHERE YEAR(start_date)='" . $nowDate->format('Y') . "'";
                $data = Yii::app()->db->createCommand($sql)->execute();
            }else{
                if(!empty($arrpara['emp'])){
                    $emp = Employee::model()->findByPk($arrpara['emp']);
                    if($emp){
                        $leaveService->calcAnnualLeaveSummaryOnBoardDate_FiscalYear($nowDate, $emp);
                    }
                }else{
                    $emp = $empService->findEmployeeInRolesListObject([2,5,26,33]);//列出文訊正職員工狀態為啟用中的
                    if($emp){
                        foreach($emp as $key => $value) {
                            $employee = Employee::model()->findByPk($value["id"]);
                            $leaveService->calcAnnualLeaveSummaryOnBoardDate_FiscalYear($nowDate, $employee);
                        }
                    }
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            echo $e->getMessage();
            Yii::log(date("Y-m-d H:i:s").' AnnualLeaveCommand employee_id => ' . $emp->id . "；nowDate => " . $nowDate->format('Y-m-d') . "；errorMsg => " . $e->getMessage(), CLogger::LEVEL_INFO);
            $transaction->rollback();
            exit;
        }
        echo "DONE\n";
    }
}