<?php

declare(strict_types=1);

use Wenhsun\Tool\FIleTool;
use Wenhsun\Tool\Uuid;

class DocumentController extends Controller
{
    private $filePath = DATA_PATH . 'document';
    private $document_department = [1=>"文訊",2=>"基金會",3=>"紀州庵"];
    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {   
        $document_department = 1;
        if(isset($_GET['document_department']) && !empty($_GET['document_department'])){
            $document_department = $_GET['document_department'];
            $list = Document::model()->byUpdateAt()->findAll(
                'document_department=:document_department',
                [':document_department' => $document_department]
            );
        }else{
            $list = Document::model()->byUpdateAt()->findAll();
        }
        
        $this->render('list', ['list' => $list, "document_department"=>$this->document_department]);
    }

    public function actionNew()
    {
        $documentTypes = DocumentType::model()->findAll();

        $this->render('new', ['documentTypes' => $documentTypes, "document_department"=>$this->document_department]);
    }

    public function actionCreate()
    {
        $tx = Yii::app()->db->beginTransaction();
        try {
            $this->checkCSRF('index');

            $fileTool = new FIleTool();
            $fileName = Uuid::gen();
            $destFullFileName = $fileTool->upload($_FILES['document_file'], $this->filePath, $fileName);

            $now = Common::now();
            $document = new Document();
            $document->receiver = trim($_POST['receiver']);
            $document->title = trim($_POST['title']);
            $document->document_department = $_POST['document_department'];
            $document->document_type = $_POST['document_type'];
            $document->file_name = $_FILES['document_file']['name'];
            $document->send_text_number = trim($_POST['send_text_number']);
            $document->send_text_date = trim($_POST['send_text_date']);
            $document->case_officer = trim($_POST['case_officer']);
            $document->saved_code = trim($_POST['saved_code']);
            $document->document_file = $destFullFileName;
            $document->create_at = $now;
            $document->update_at = $now;
            $document->save();

            if ($document->hasErrors()) {
                Yii::log(serialize($document->getErrors()), CLogger::LEVEL_ERROR);
                Yii::app()->session[Controller::ERR_MSG_KEY] = '新增失敗';
                $this->redirect('new');
            }

            $tx->commit();
            switch ($_POST['document_department']) {
                case '1':
                    $this->redirect(array('index','document_department'=>1));
                    break;
                case '2':
                    $this->redirect(array('index','document_department'=>2));
                    break;
                case '3':
                    $this->redirect(array('index','document_department'=>3));
                    break;
                default:
                    $this->redirect('index');
                    break;
            }
            

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增錯誤';
            $this->redirect('new');
        } finally {
            $tx->rollback();
        }
    }

    public function actionEdit($id)
    {
        $document = Document::model()->findByPk($id);

        if (!$document) {
            Yii::log("id not found", CLogger::LEVEL_ERROR);
            $this->redirect('index');
        }

        $sendTextDateTime = new DateTime($document->send_text_date);
        $document->send_text_date = $sendTextDateTime->format("Y-m-d");

        $documentTypes = DocumentType::model()->findAll();

        $this->render('edit', ['data' => $document, 'documentTypes' => $documentTypes, "document_department"=>$this->document_department]);
    }

    public function actionUpdate()
    {
 
        $id = $_POST['id'];
        $tx = Yii::app()->db->beginTransaction();
        try {

            $document = Document::model()->findByPk($id);

            if (!$document) {
                Yii::log("id not found", CLogger::LEVEL_ERROR);
                $this->redirect('index');
            }

            $now = Common::now();
            $document->receiver = trim($_POST['receiver']);
            $document->title = trim($_POST['title']);
            $document->document_department = $_POST['document_department'];
            $document->document_type = $_POST['document_type'];
            $document->send_text_number = trim($_POST['send_text_number']);
            $document->send_text_date = trim($_POST['send_text_date']);
            $document->case_officer = trim($_POST['case_officer']);
            $document->saved_code = trim($_POST['saved_code']);
            $document->update_at = $now;

            if (!empty($_FILES['document_file']['name'])) {
                $oriFile = $document->document_file;
                $document->file_name = $_FILES['document_file']['name'];
                $fileTool = new FIleTool();
                $fileName = Uuid::gen();
                $destFullFileName = $fileTool->upload($_FILES['document_file'], $this->filePath, $fileName);
                $document->document_file = $destFullFileName;
            }

            $document->save();

            if ($document->hasErrors()) {
                Yii::log(serialize($document->getErrors()), CLogger::LEVEL_ERROR);
                Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
                $this->redirect('edit?id=' . $id);
            }

            if (isset($oriFile) && file_exists($oriFile)) {
                unlink($oriFile);
            }

            $tx->commit();

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("index?document_department={$_POST['document_department']}");
            // $this->redirect("edit?id={$_POST['id']}");

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新錯誤';
            $this->redirect('edit?id=' . $id);
        } finally {
            $tx->rollback();
        }
    }

    public function actionDelete()
    {
        try {
            $this->checkCsrfAjax();

            $pk = filter_input(INPUT_POST, 'id');
            $employee = Document::model()->findByPk($pk);
            if (!$employee) {
                Yii::app()->session[Controller::ERR_MSG_KEY] = '刪除失敗，資料不存在';
                $this->sendErrAjaxRsp(404, "資料不存在");
            }
            if($employee->delete()){
                Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '刪除成功';
            }else{
                Yii::app()->session[Controller::ERR_MSG_KEY] = '刪除失敗' . json_encode($document->getErrors(), JSON_UNESCAPED_UNICODE);
            }
            // $employee->delete();
            $this->sendSuccAjaxRsp();

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '刪除失敗' . $ex->getMessage();
            $this->sendErrAjaxRsp(500, "系統錯誤");
        }
    }

    public function actionDownload($id)
    {
        try {

            $this->layout = false;

            $document = Document::model()->findByPk($id);

            if (!$document) {
                Yii::log("id not found", CLogger::LEVEL_ERROR);
                echo "無文件可下載";
                return false;
            }

            if (!file_exists($document->document_file)) {
                Yii::log("image not found", CLogger::LEVEL_ERROR);
                echo "無文件可下載";
                return false;
            }

            $output = "";
            $fd = fopen($document->document_file, "r");
            while (!(feof($fd))) {
                $output .= fread($fd, 8192);
            }
            fclose($fd);

            header('Content-Disposition: attachment; filename=' . $document->file_name);
            header("Pragma: no-cache");
            header("Expires: 0");
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            echo $output;

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            $this->redirect('index');
        }
    }
}