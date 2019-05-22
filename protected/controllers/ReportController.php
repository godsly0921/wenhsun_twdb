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
        $count_eachday_upload = $reportService->countEachdayUpload(); // 統計近 20 天的上圖張數
        $count_single_size = $reportService->countSingleSize();
        $count_single = $reportService->countSingle();
        $count_single_publish = $reportService->countSinglePublish();
        $top_profile = $reportService->topProfile();
        $this->render('system',array( 'count_eachday_upload'=>$count_eachday_upload, 'count_single_size'=>$count_single_size, 'count_single'=>$count_single, 'count_single_publish'=>$count_single_publish, 'top_profile'=>$top_profile ));
    }

    public function ActionOrder(){
        $reportService = new ReportService();
        $count_eachday_upload = $reportService->countEachdayOrder(); // 統計近 20 天的總銷售額
        $count_order_sum = $reportService->getSumOrder(); // 統計總銷售額、點數銷售額、自由載銷售額、單圖銷售額
        $this->render('order');
    }
}
?>