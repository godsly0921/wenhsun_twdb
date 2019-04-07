<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 2015/7/11
 * Time: 下午 05:07
 */
class NewsController extends Controller
{
    private $defaultLanguageType = ["zh-tw"=>"繁體中文","zh-cn"=>"简体中文","en"=> "English"];
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }
    
    public function actionIndex()
    {
        $newsService = new NewsService();
        $news = $newsService->findAdminNews();

        $service = new AccountService();
        $account = $service->findAccounts();//取出系統管理員帳號

        $this->render('index', ["news" => $news,'account'=>$account]);
    }

    public function actionCreate()
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostCreate() : $this->doGetCreate();
    }

    private function doPostCreate()
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect('index');
        }

        $inputs = [];
        $inputs["new_title"] = filter_input(INPUT_POST, "new_title");
        $inputs["new_content"] = filter_input(INPUT_POST, "new_content");
        $inputs["new_image"] = $_FILES["new_image"];
        $inputs["new_type"] = filter_input(INPUT_POST, "new_type");
        $inputs["sort"] = filter_input(INPUT_POST, "sort");


        //remember fields
        foreach ($inputs as $key => $val) {
            Yii::app()->session[$key] = $val;
        }

        $newsService = new NewsService();
        $newsModel = $newsService->create($inputs);

        if ($newsModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $newsModel->getErrors();
            $this->redirect('create');
        } else {
            //if success should clear form fields session
            foreach ($inputs as $key => $val) {
                Yii::app()->session[$key] = "";
            }
            $this->redirect('index');
        }
    }

    private function doGetCreate()
    {
        CsrfProtector::putToken();
        $this->render('create');
        $this->clearMsg();
    }

    /**
     * @param $id
     */
    public function actionUpdate($id = null)
    {
        ($_SERVER['REQUEST_METHOD'] === "POST") ? $this->doPostUpdate() : $this->doGetUpdate($id);

    }

    private function doPostUpdate()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $inputs = [];
        $inputs["id"] = filter_input(INPUT_POST, "id");
        $inputs["new_title"] = filter_input(INPUT_POST, "new_title");
        $inputs["new_content"] = filter_input(INPUT_POST, "new_content");
        $inputs["new_image_old"] = filter_input(INPUT_POST, "new_image_old");
        $inputs["new_image"] = $_FILES["new_image"];
        $inputs["new_createtime"] = filter_input(INPUT_POST, "new_createtime");
        $inputs["new_type"] = filter_input(INPUT_POST, "new_type");
        $inputs["sort"] = filter_input(INPUT_POST, "sort");

        $newsService = new NewsService();
        $newsModel = $newsService->updateNews($inputs);

        if ($newsModel->hasErrors()) {
            Yii::app()->session['error_msg'] = $newsModel->getErrors();
        } else {
            Yii::app()->session['success_msg'] = '修改消息成功';
        }

        $this->redirect('update/'.$inputs['id']);
    }

    private function doGetUpdate($id)
    {
        $news = News::model()->findByPk($id);

        if ($news !== null) {
            $this->render('update',['news' => $news,'languages'=>$this->defaultLanguageType]);
            $this->clearMsg();
        } else {
            $this->redirect(Yii::app()->createUrl('index'));
        }
    }

    /**
     * 新聞刪除
     */
    public function actionDelete()
    {
        ($_SERVER['REQUEST_METHOD'] === 'POST') ? $this->doPostDelete() : $this->redirect(['index']);
    }

    private function doPostDelete()
    {
        if (!CsrfProtector::comparePost())
            $this->redirect('index');

        $id = filter_input(INPUT_POST, 'id');

        $news = News::model()->findByPk($id);

        if ($news !== null) {
            $news -> delete();
            $this->redirect(['index']);
        }
    }
    
    // 公佈欄功能
    public function actionlist(){

        $newService = new NewsService;
        $accountService = new AccountService();
        $deviceService = new DeviceService;
        $recordService = new Recordservice;
        $doorService = new Doorservice;
        $data    = $newService -> findNews();
        $saw     = $newService -> havesaw(Yii::app()->session['uid']);
        $total   = count( $data );
        $sawarr = array();

        foreach ($saw as $key => $value) {
          array_push($sawarr,(int)$value->news_id);
        }
        $account = $accountService->findAccounts();//取出系統管理員帳號

        // ----- Start 儀器即時監控 Start -----
        $deviceMonitor = $deviceService->get_all_for_monitor();      
        // 取最後刷卡時間跟刷卡人
        foreach ($deviceMonitor as $key => $value) {
            $tmp = $recordService->getlastrecord( $value[ 'station' ] );
            $deviceMonitor[ $key ][ 'username' ] = $tmp[ 'name' ];
            $deviceMonitor[ $key ][ 'usedate' ] = $tmp[ 'flashDate' ];
        }
        // ----- End 儀器即時監控 End -----

        // ----- Start 門禁即時監控 Start -----
        $doorMonitor = $doorService->get_all();
        // 取最後刷卡時間跟刷卡人
        foreach ($doorMonitor as $key => $value) {
            $tmp = $recordService->getlastrecord( $value[ 'station' ] );
            $doorMonitor[ $key ][ 'username' ] = $tmp[ 'name' ];
            $doorMonitor[ $key ][ 'usedate' ] = $tmp[ 'flashDate' ];
        }
        // ----- End 門禁即時監控 End -----
        
        $this->render('list',['data'=>$data,'total'=>$total,'sawarr'=>$sawarr,'account' =>$account,'deviceMonitor'=>$deviceMonitor,'doorMonitor'=>$doorMonitor]);

    }

    public function actionnewsview(){
        
        if( !empty($_POST['news']) && !empty($_POST['mid']) ){
            $service = new NewsService;
            $res     = $service->addview($_POST['news'],$_POST['mid']);
            echo json_encode($res);
        }
    }

    public function actiondownloadpdf( $fileName ){
        
        $newsSer = new NewsService();
        $newsRes = $newsSer->findById($fileName);
        $tmpUrl  = substr($newsRes->new_image,1);
        Yii::app()->getRequest()->sendFile( 'dname.pdf' , file_get_contents( $tmpUrl ) );
    }
}