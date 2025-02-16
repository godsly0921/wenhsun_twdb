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
        $recordsTotal = $reportService->countOperationLog("");
        // $all_operation_log = $reportService->AllOperationLog();
        $this->render('system',array( 
            'count_eachday_upload'=>$count_eachday_upload, 
            'count_single_size'=>$count_single_size, 
            'count_single'=>$count_single, 
            'count_single_publish'=>$count_single_publish, 
            'top_profile'=>$top_profile,
            'count_operation_log' => $recordsTotal['total'] ? $recordsTotal['total'] :0
        ));
    }
    public function ActionAjaxOperationLog(){
        // 獲取 DataTable 發送的參數
        $draw = $_POST['draw'];
        $start = $_POST['start']; // 當前頁的起始記錄
        $length = $_POST['length']; // 每頁顯示多少條數據
        $search = $_POST['search'] ? $_POST['search'] : ""; // 搜索框中的搜尋條件
        $reportService = new ReportService();
        $all_operation_log = $reportService->GetOperationLogWithPageLimit($_POST['search'], $length, $start);
        $recordsTotal = $reportService->countOperationLog($search);
        // 返回 DataTable 所需的數據格式
        $response = [
            "draw" => $draw,
            "recordsTotal" => (int)$recordsTotal['total'],
            "recordsFiltered" => (int)$recordsTotal['total'],  // 可以根據過濾條件更改這裡
            "data" => $all_operation_log
        ];

        echo json_encode($response);
        // $post_data = $_POST;
        // var_dump($post_data);
    }

    public function ActionOrder(){
        $reportService = new ReportService();
        $count_eachday_order = $reportService->countEachdayOrder(); // 統計近 20 天的總銷售額
        $count_order_sum = $reportService->getSumOrder(); // 統計總銷售額、點數銷售額、自由載銷售額、單圖銷售額
        $top3_order = $reportService->top3_Order(); // 最近 3 筆訂單資訊
        $all_order = $reportService->Allorder(); // 所有的訂單
        //var_dump($count_eachday_order);exit();
        $this->render('order',array('count_order_sum'=>$count_order_sum,'count_eachday_order'=>$count_eachday_order,'top3_order'=>$top3_order,'all_order'=>$all_order));
    }
}
?>