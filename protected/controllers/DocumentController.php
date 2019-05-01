<?php

declare(strict_types=1);

use Wenhsun\Tool\FIleTool;
use Wenhsun\Tool\Uuid;

class DocumentController extends Controller
{
    private $filePath = DATA_PATH . 'document';

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $this->render('list');
    }

    public function actionNew()
    {
        $documentTypes = DocumentType::model()->findAll();

        $this->render('new', ['documentTypes' => $documentTypes]);
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
            $document->document_type = $_POST['document_type'];
            $document->send_text_number = trim($_POST['send_text_number']);
            $document->send_text_date = trim($_POST['send_text_date']);
            $document->case_officer = trim($_POST['case_officer']);
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

            $this->redirect('index');

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增錯誤';
            $this->redirect('new');
        } finally {
            $tx->rollback();
        }
    }
}