<?php

use Wenhsun\Transform\MultiColumnTransformer;

class AuthorController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionIndex()
    {
        $authors = Author::model()->findAll();

        $this->render('list', ['list' => $authors]);
    }

    public function actionNew()
    {
        $this->render('new');
    }

    public function actionCreate()
    {
        $this->checkCSRF('index');
        $now = Common::now();
        $data = filter_input_array(INPUT_POST);

        $multiTransfer = new MultiColumnTransformer();

        $author = new Author();
        $author->author_name = $data['author_name'];
        $author->gender = $data['gender'];
        $author->birth = $data['birth'];
        $author->death = (!empty($data['death'])) ? $data['death'] : null;
        $author->job_title = $data['job_title'];
        $author->service = $data['service'];
        $author->identity_type = (!empty($data['identity_type'])) ? implode(',', $data['identity_type']) : null;
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
        $author->create_at = $now;
        $author->update_at = $now;
        $author->save();
        $pk = Yii::app()->db->getLastInsertID();

        if ($author->hasErrors()) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '新增使用者失敗';
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

        $this->redirect('index');
    }

    public function actionEdit($id)
    {
        $author = Author::model()->findByPk($id);

        if (!$author) {
            $this->redirect('index');
        }

        $multiTransfer = new MultiColumnTransformer();
        $author->office_address = $multiTransfer->toText(';', $author->office_address);
        $author->office_phone = $multiTransfer->toText(';', $author->office_phone);
        $author->office_fax = $multiTransfer->toText(';', $author->office_fax);
        $author->email = $multiTransfer->toText(';', $author->email);
        $author->home_address = $multiTransfer->toText(';', $author->home_address);
        $author->home_phone = $multiTransfer->toText(';', $author->home_phone);
        $author->home_fax = $multiTransfer->toText(';', $author->home_fax);
        $author->mobile = $multiTransfer->toText(';', $author->mobile);
        $author->social_account = $multiTransfer->toText(';', $author->social_account);
        $author->identity_number = $multiTransfer->toText(';', $author->identity_number);
        $author->pen_name = $multiTransfer->toText(';', $author->pen_name);

        $bankList = [];

        $banks = AuthorBank::model()->findAll(
            "author_id=:author_id",
            [':author_id' => $id]
        );

        $bankList[0] = (isset($banks[0])) ? $banks[0] : new AuthorBank();
        $bankList[1] = (isset($banks[1])) ? $banks[1] : new AuthorBank();

        $this->render('edit', ['data' => $author, 'bank_list' => $bankList]);
    }

    public function actionUpdate()
    {
        try {

            $this->checkCSRF('index');
            $now = Common::now();
            $data = filter_input_array(INPUT_POST);

            $multiTransfer = new MultiColumnTransformer();
            $authorId = $data['author_id'];
            $author = Author::model()->findByPk($data['author_id']);

            if (!$author) {
                $this->redirect("index");
            }

            $author->author_name = $data['author_name'];
            $author->gender = $data['gender'];
            $author->birth = $data['birth'];
            $author->death = (!empty($data['death'])) ? $data['death'] : null;
            $author->job_title = $data['job_title'];
            $author->service = $data['service'];
            $author->identity_type = (!empty($data['identity_type'])) ? implode(',', $data['identity_type']) : null;
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
                Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
                $this->redirect("edit?id={$authorId}");
            }

            if (empty($data['author_bank_id'])) {
                $authBank = new AuthorBank();
                $authBank->author_id = $authorId;
                $authBank->bank_name = $data['bank_name'];
                $authBank->bank_code = $data['bank_code'];
                $authBank->branch_name = $data['branch_name'];
                $authBank->branch_code = $data['branch_code'];
                $authBank->bank_account = $data['bank_account'];
                $authBank->account_name = $data['account_name'];
                $authBank->create_at = $now;
                $authBank->update_at = $now;
                $authBank->save();
            } else {
                $authBank = AuthorBank::model()->findByPk($data['author_bank_id']);
                $authBank->author_id = $authorId;
                $authBank->bank_name = $data['bank_name'];
                $authBank->bank_code = $data['bank_code'];
                $authBank->branch_name = $data['branch_name'];
                $authBank->branch_code = $data['branch_code'];
                $authBank->bank_account = $data['bank_account'];
                $authBank->account_name = $data['account_name'];
                $authBank->update_at = $now;
                $authBank->update();
            }

            if (empty($data['author_bank_id_2'])) {
                $authBank = new AuthorBank();
                $authBank->author_id = $authorId;
                $authBank->bank_name = $data['bank_name2'];
                $authBank->bank_code = $data['bank_code2'];
                $authBank->branch_name = $data['branch_name2'];
                $authBank->branch_code = $data['branch_code2'];
                $authBank->bank_account = $data['bank_account2'];
                $authBank->account_name = $data['account_name2'];
                $authBank->create_at = $now;
                $authBank->update_at = $now;
                $authBank->save();
            } else {
                $authBank = AuthorBank::model()->findByPk($data['author_bank_id_2']);
                $authBank->author_id = $authorId;
                $authBank->bank_name = $data['bank_name2'];
                $authBank->bank_code = $data['bank_code2'];
                $authBank->branch_name = $data['branch_name2'];
                $authBank->branch_code = $data['branch_code2'];
                $authBank->bank_account = $data['bank_account2'];
                $authBank->account_name = $data['account_name2'];
                $authBank->update_at = $now;
                $authBank->update();
            }

            Yii::app()->session[Controller::SUCCESS_MSG_KEY] = '更新成功';
            $this->redirect("edit?id={$authorId}");

        } catch (Throwable $ex) {
            Yii::app()->session[Controller::ERR_MSG_KEY] = '更新失敗';
        }
    }
}