<?php
class ReportController extends Controller{
    // layout
    public $layout = "//layouts/back_end";
    
    // 登入驗證
    protected function needLogin(): bool
    {
        return true;
    }

    public function ActionSystem(){
        $reportService = new ReportService();
        $count_eachday_upload = $reportService->countEachdayUpload();
        $this->render('system',array('count_eachday_upload'=>$count_eachday_upload));
    }

    public function ActionOrder(){
        $this->render('order');
    }
}
?>