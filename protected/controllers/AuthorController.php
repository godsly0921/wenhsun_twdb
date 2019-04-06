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
        $this->render('list');
    }

    public function actionNew()
    {
        $this->render('new');
    }

    public function actionCreate()
    {
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

        echo "ok";
    }

    public function actionCreateForm()
    {
        $this->render('create_form');
    }
}