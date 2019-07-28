<?php

use Wenhsun\Tool\ModelErrorBuilder;
use Wenhsun\Transform\MultiColumnTransformer;
use yidas\phpSpreadsheet\Helper;

class AuthorController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex(): void
    {
        $searchCategory = $_POST['search_category'] ?? '';
        $searchOne = $_POST['search_one'] ?? '';
        $searchTwo = $_POST['search_two'] ?? '';

        $authors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search_category'])) {
            $authors = $this->query($searchCategory, $searchOne, $searchTwo);
        }
		$this->render(
		    'list',
            [
                'list' => $authors,
                'searchCategory' => $searchCategory,
                'searchOne' => $searchOne,
                'searchTwo' => $searchTwo,
            ]
        );
    }

    private function getGenderText(string $gender): string
    {
        switch ($gender) {
            case 'M':
                return '男';
                break;
            case 'F':
                return '女';
                break;
            default:
                return '未設定';
        }
    }

    public function actionExport(): void
    {
        $this->checkCSRF('index');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index');
        }

        $searchCategory = $_POST['search_category'] ?? '';
        $searchOne = $_POST['search_one'] ?? '';
        $searchTwo = $_POST['search_two'] ?? '';
        if($searchCategory == "" && $searchOne == "" && $searchTwo == ""){
            $fileName = "作家資料匯出";
        }else{
            $fileName = "作家資料匯出_{$searchCategory}_{$searchOne}_{$searchTwo}";
        }
        set_time_limit(0);
        ini_set('memory_limit', '1024M');
        $multiTransfer = new MultiColumnTransformer();

        $authors = $this->query($searchCategory, $searchOne, $searchTwo);
        $rows = [];

        foreach ($authors as $index => $author) {

            $banks = AuthorBank::model()->findAll(
                'author_id=:author_id',
                [':author_id' => $author['id']]
            );

            $rows[] = [
                $multiTransfer->toText('；', $author['pen_name']),
                $author['author_name'],
                $this->getGenderText($author['gender']),
                str_replace('-', '/', $author['birth']),
                str_replace('-', '/', $author['death']),
                $multiTransfer->toText('；', $author['email']),
                $multiTransfer->toText('；', $author['office_address']),
                $author['service'],
                $author['job_title'],
                $multiTransfer->toText('；', $author['office_phone']),
                $multiTransfer->toText('；', $author['office_fax']),
                $multiTransfer->toText('；', $author['home_address']),
                $multiTransfer->toText('；', $author['home_phone']),
                $multiTransfer->toText('；', $author['home_fax']),
                $multiTransfer->toText('；', $author['mobile']),
                !empty($author['identity_type']) ? implode(',', json_decode($author['identity_type'], true)) : '',
                $multiTransfer->toText('；', $author['social_account']),
                $author['memo'],
                $author['nationality'],
                $multiTransfer->toText('；', $author['identity_number']),
                $author['residence_address'],
                $banks[0]->bank_name ?? '',
                $banks[0]->bank_code ?? '',
                $banks[0]->branch_name ?? '',
                $banks[0]->branch_code ?? '',
                $banks[0]->bank_account ?? '',
                $banks[0]->account_name ?? '',
                $banks[1]->bank_name ?? '',
                $banks[1]->bank_code ?? '',
                $banks[1]->branch_name ?? '',
                $banks[1]->branch_code ?? '',
                $banks[1]->bank_account ?? '',
                $banks[1]->account_name ?? '',
            ];
        }

        Helper::newSpreadsheet()
            ->addRow([
                '筆名',
                '姓名',
                '性別',
                '生日',
                '卒日',
                '電子郵件',
                '公司 郵遞區號/地址',
                '服務單位',
                '職稱',
                '辦公電話',
                '辦公傳真',
                '住家 郵遞區號/地址',
                '住家電話',
                '住家傳真',
                '手機',
                '身份類型',
                '網路社群帳號',
                '備註',
                '國籍',
                '身分證字號/護照號碼/統一編號',
                '戶籍地',
                '銀行名稱(1)',
                '銀行代碼(1)',
                '分行名稱(1)',
                '分行代碼(1)',
                '帳號(1)',
                '戶名(1)',
                '銀行名稱(2)',
                '銀行代碼(2)',
                '分行名稱(2)',
                '分行代碼(2)',
                '帳號(2)',
                '戶名(2)'
            ])
            ->addRows(
                $rows
            )
            ->output($fileName);
    }

    private function query(string $searchCategory, string $searchOne, string $searchTwo): array
    {
        $authorServ = new \Wenhsun\Author\AuthorService();

        switch ($searchCategory) {
            case 'pen_name':
                return $authorServ->queryByPenName($searchOne);
                break;
            case 'author_name':
                return $authorServ->queryByAuthorName($searchOne);
                break;
            case 'birth_year':
                return $authorServ->queryByBirthYear($searchOne, $searchTwo);
                break;
            case 'service':
                return $authorServ->queryByService($searchOne);
                break;
            case 'job_title':
                return $authorServ->queryByJobTitle($searchOne);
                break;
            case 'address':
                return $authorServ->queryByAddress($searchOne);
                break;
            case 'identity_type':
                return $authorServ->queryByIdentityType($searchOne);
                break;
            case 'memo':
                return $authorServ->queryByMemo($searchOne);
                break;
            default:
                return $authorServ->queryAll();
        }
    }

    public function actionNew()
    {
        $this->render('new');
    }

    /**
     * @throws CException
     */
    public function actionCreate(): void
    {
        $this->checkCSRF('index');
        $now = Common::now();
        $data = filter_input_array(INPUT_POST);

        $multiTransfer = new MultiColumnTransformer();
        $author = new Author();
        $transaction = $author->dbConnection->beginTransaction();

        try {

            $author->author_name = $data['author_name'];
            $author->gender = $data['gender'];

            if (!empty($data['birth'])) {
                $author->birth = $data['birth'];
                $author->birth_year = explode('/', $data['birth'])[0];
            } else {
                $author->birth = null;
                $author->birth_year = null;
            }

            $author->death = !empty($data['death']) ? $data['death'] : null;
            $author->job_title = $data['job_title'];
            $author->service = $data['service'];
            $author->identity_type = !empty($data['identity_type']) ? json_encode($data['identity_type'], JSON_UNESCAPED_UNICODE) : null;
            $author->nationality = $data['nationality'];
            $author->residence_address = $data['residence_address'];
            $author->office_address = $multiTransfer->toJson('；', $data['office_address']);
            $author->office_phone = $multiTransfer->toJson('；', $data['office_phone']);
            $author->office_fax = $multiTransfer->toJson('；', $data['office_fax']);
            $author->email = $multiTransfer->toJson('；', $data['email']);
            $author->home_address = $multiTransfer->toJson('；', $data['home_address']);
            $author->home_phone = $multiTransfer->toJson('；', $data['home_phone']);
            $author->home_fax = $multiTransfer->toJson('；', $data['home_fax']);
            $author->mobile = $multiTransfer->toJson('；', $data['mobile']);
            $author->social_account = $multiTransfer->toJson('；', $data['social_account']);
            $author->memo = $data['memo'];
            $author->identity_number = $multiTransfer->toJson('；', $data['identity_number']);
            $author->pen_name = $multiTransfer->toJson('；', $data['pen_name']);
            $author->create_at = $now;
            $author->update_at = $now;
            $author->save();

            $pk = Yii::app()->db->getLastInsertID();

            if ($author->hasErrors()) {
                $errorBuilder = new ModelErrorBuilder();
                Yii::app()->session[Controller::ERR_MSG_KEY] = $errorBuilder->modelErrors2Html($author->getErrors());
                $this->redirect('new');
            }

            $authBank = new AuthorBank();
            $authBank->author_id = $pk;
            $authBank->bank_name = $data['bank_name'];
            $authBank->bank_code = $data['bank_code'];
            $authBank->branch_name = $data['branch_name'];
            $authBank->branch_code = $data['branch_code'];
            $authBank->bank_account = $data['bank_account'];
            $authBank->account_name = $data['account_name'];
            $authBank->create_at = $now;
            $authBank->update_at = $now;
            $authBank->save();

            if ($authBank->hasErrors()) {
                $errorBuilder = new ModelErrorBuilder();
                Yii::app()->session[Controller::ERR_MSG_KEY] = $errorBuilder->modelErrors2Html($authBank->getErrors());
                $this->redirect('new');
            }

            $authBank = new AuthorBank();
            $authBank->author_id = $pk;
            $authBank->bank_name = $data['bank_name2'];
            $authBank->bank_code = $data['bank_code2'];
            $authBank->branch_name = $data['branch_name2'];
            $authBank->branch_code = $data['branch_code2'];
            $authBank->bank_account = $data['bank_account2'];
            $authBank->account_name = $data['account_name2'];
            $authBank->create_at = $now;
            $authBank->update_at = $now;
            $authBank->save();

            if ($authBank->hasErrors()) {
                $errorBuilder = new ModelErrorBuilder();
                Yii::app()->session[Controller::ERR_MSG_KEY] = $errorBuilder->modelErrors2Html($authBank->getErrors());
                $this->redirect('new');
            }

            $transaction->commit();

            $this->redirect('index');

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增使用者失敗';
            $this->redirect('new');
        } finally {
            $transaction->rollback();
        }
    }

    public function actionEdit($id): void
    {
        $author = Author::model()->findByPk($id);

        if (!$author) {
            $this->redirect('index');
        }

        $multiTransfer = new MultiColumnTransformer();
        $author->birth = str_replace('-', '/', $author->birth);
        $author->death = str_replace('-', '/', $author->death);
        $author->office_address = $multiTransfer->toText('；', $author->office_address);
        $author->office_phone = $multiTransfer->toText('；', $author->office_phone);
        $author->office_fax = $multiTransfer->toText('；', $author->office_fax);
        $author->email = $multiTransfer->toText('；', $author->email);
        $author->home_address = $multiTransfer->toText('；', $author->home_address);
        $author->home_phone = $multiTransfer->toText('；', $author->home_phone);
        $author->home_fax = $multiTransfer->toText('；', $author->home_fax);
        $author->mobile = $multiTransfer->toText('；', $author->mobile);
        $author->social_account = $multiTransfer->toText('；', $author->social_account);
        $author->identity_number = $multiTransfer->toText('；', $author->identity_number);
        $author->identity_type = !empty($author->identity_type) ? json_decode($author->identity_type, true) : [];
        $author->pen_name = $multiTransfer->toText('；', $author->pen_name);

        $bankList = [];

        $banks = AuthorBank::model()->findAll(
            'author_id=:author_id',
            [':author_id' => $id]
        );

        $bankList[0] = $banks[0] ?? new AuthorBank();
        $bankList[1] = $banks[1] ?? new AuthorBank();

        $this->render('edit', ['data' => $author, 'bank_list' => $bankList]);
    }

    public function actionView($id): void
    {
        $author = Author::model()->findByPk($id);

        if (!$author) {
            $this->redirect('index');
        }

        $multiTransfer = new MultiColumnTransformer();
        $author->birth = str_replace("-", "/", $author->birth);
        $author->death = str_replace("-", "/", $author->death);
        $author->office_address = $multiTransfer->toText('；', $author->office_address);
        $author->office_phone = $multiTransfer->toText('；', $author->office_phone);
        $author->office_fax = $multiTransfer->toText('；', $author->office_fax);
        $author->email = $multiTransfer->toText('；', $author->email);
        $author->home_address = $multiTransfer->toText('；', $author->home_address);
        $author->home_phone = $multiTransfer->toText('；', $author->home_phone);
        $author->home_fax = $multiTransfer->toText('；', $author->home_fax);
        $author->mobile = $multiTransfer->toText('；', $author->mobile);
        $author->social_account = $multiTransfer->toText('；', $author->social_account);
        $author->identity_number = $multiTransfer->toText('；', $author->identity_number);
        $author->identity_type = !empty($author->identity_type) ? json_decode($author->identity_type, true) : [];
        $author->pen_name = $multiTransfer->toText('；', $author->pen_name);

        $bankList = [];

        $banks = AuthorBank::model()->findAll(
            "author_id=:author_id",
            [':author_id' => $id]
        );

        $bankList[0] = (isset($banks[0])) ? $banks[0] : new AuthorBank();
        $bankList[1] = (isset($banks[1])) ? $banks[1] : new AuthorBank();

        $this->render('view', ['data' => $author, 'bank_list' => $bankList]);
    }

    /**
     * @throws CException
     */
    public function actionUpdate()
    {
        $this->checkCSRF('index');
        $now = Common::now();
        $data = filter_input_array(INPUT_POST);

        $multiTransfer = new MultiColumnTransformer();
        $authorId = $data['author_id'];
        $author = Author::model()->findByPk($data['author_id']);

        if (!$author) {
            $this->redirect("index");
        }

        $transaction = $author->dbConnection->beginTransaction();

        try {

            $author->author_name = $data['author_name'];
            $author->gender = $data['gender'];

            if (!empty($data['birth'])) {
                $author->birth = $data['birth'];
                $author->birth_year = explode('/', $data['birth'])[0];
            } else {
                $author->birth = null;
                $author->birth_year = null;
            }

            $author->death = (!empty($data['death'])) ? $data['death'] : null;
            $author->job_title = $data['job_title'];
            $author->service = $data['service'];
            $author->identity_type = !empty($data['identity_type']) ? $author->identity_type = json_encode($data['identity_type'], JSON_UNESCAPED_UNICODE) : null;
            $author->nationality = $data['nationality'];
            $author->residence_address = $data['residence_address'];
            $author->office_address = $multiTransfer->toJson(';', $data['office_address']);
            $author->office_phone = $multiTransfer->toJson(';', $data['office_phone']);
            $author->office_fax = $multiTransfer->toJson(';', $data['office_fax']);
            $author->email = $multiTransfer->toJson(';', $data['email']);
            $author->home_address = $multiTransfer->toJson(';', $data['home_address']);
            $author->home_phone = $multiTransfer->toJson(';', $data['home_phone']);
            $author->home_fax = $multiTransfer->toJson(';', $data['home_fax']);
            $author->mobile = $multiTransfer->toJson(';', $data['mobile']);
            $author->social_account = $multiTransfer->toJson(';', $data['social_account']);
            $author->memo = $data['memo'];
            $author->identity_number = $multiTransfer->toJson(';', $data['identity_number']);
            $author->pen_name = $multiTransfer->toJson(';', $data['pen_name']);
            $author->update_at = $now;
            $author->update();

            if ($author->hasErrors()) {
                $errorBuilder = new ModelErrorBuilder();
                Yii::app()->session[Controller::ERR_MSG_KEY] = $errorBuilder->modelErrors2Html($author->getErrors());
                $this->redirect("edit?id={$authorId}");
            }

            if (empty($data['author_bank_id'])) {
                $authBank = new AuthorBank();
                $authBank->create_at = $now;
            } else {
                $authBank = AuthorBank::model()->findByPk($data['author_bank_id']);
            }

            $authBank->author_id = $authorId;
            $authBank->bank_name = $data['bank_name'];
            $authBank->bank_code = $data['bank_code'];
            $authBank->branch_name = $data['branch_name'];
            $authBank->branch_code = $data['branch_code'];
            $authBank->bank_account = $data['bank_account'];
            $authBank->account_name = $data['account_name'];
            $authBank->update_at = $now;
            $authBank->save();

            if ($authBank->hasErrors()) {
                $errorBuilder = new ModelErrorBuilder();
                Yii::app()->session[Controller::ERR_MSG_KEY] = $errorBuilder->modelErrors2Html($authBank->getErrors());
                $this->redirect("edit?id={$authorId}");
            }

            if (empty($data['author_bank_id_2'])) {
                $authBank = new AuthorBank();
                $authBank->create_at = $now;
            } else {
                $authBank = AuthorBank::model()->findByPk($data['author_bank_id_2']);
            }

            $authBank->author_id = $authorId;
            $authBank->bank_name = $data['bank_name2'];
            $authBank->bank_code = $data['bank_code2'];
            $authBank->branch_name = $data['branch_name2'];
            $authBank->branch_code = $data['branch_code2'];
            $authBank->bank_account = $data['bank_account2'];
            $authBank->account_name = $data['account_name2'];

            $authBank->update_at = $now;
            $authBank->save();

            if ($authBank->hasErrors()) {
                $errorBuilder = new ModelErrorBuilder();
                Yii::app()->session[Controller::ERR_MSG_KEY] = $errorBuilder->modelErrors2Html($authBank->getErrors());
                $this->redirect("edit?id={$authorId}");
            }

            $transaction->commit();

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("edit?id={$authorId}");

        } catch (Throwable $ex) {
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
            $this->redirect("edit?id={$authorId}");
        } finally {
            $transaction->rollback();
        }
    }

    /**
     * @throws CException
     */
    public function actionDelete()
    {
        $this->checkCsrfAjax();
        $pk = filter_input(INPUT_POST, 'id');

        $author = Author::model()->findByPk($pk);

        if (!$author) {
            $this->sendErrAjaxRsp(404, "資料不存在");
        }

        $transaction = $author->dbConnection->beginTransaction();

        try {

            $author->delete();

            $banks = AuthorBank::model()->findAll(
                'author_id=:author_id',
                [':author_id' => $pk]
            );

            if ($banks) {
                foreach ($banks as $bank) {
                    $bank->delete();
                }
            }

            $transaction->commit();

            $this->sendSuccAjaxRsp();

        } catch (Throwable $ex) {
            $transaction->rollback();
            Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR);
            $this->sendErrAjaxRsp(500, "系統錯誤");
        }
    }
}