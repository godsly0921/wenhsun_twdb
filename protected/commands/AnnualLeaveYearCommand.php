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
            $nowDate->setTime(0, 0, 0);
            $emp = $empService->findEmployeeInRolesListObject([2,5,26,33]);//列出文訊正職員工狀態為啟用中的
            if($emp){
                foreach($emp as $key => $value) {
                    $leaveService->SpecialLeaveYearIdInit($nowDate->format('Y'), $employee);
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