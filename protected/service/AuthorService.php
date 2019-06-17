<?php
//date_default_timezone_set("Asia/Taipei");
class AuthorService
{
    public static function Import($file = "")
    {

        if (true) {
            $file = '/var/www/html/assets/site/author1.xls';
            $type = true;
        } else {
            $type = false;
        }


        if ($type) {

            clearstatcache();// 函数清除文件状态缓存
            $excel_datas = Exceler::read_excel($file, 1);
            $excel_datas_total = count($excel_datas) + 1;
            $birth_year = NULL;
            for ($i = 1; $i < $excel_datas_total; $i++) {
                try {
                $now = Common::now();

                $author = new Author();
                $transaction = $author->dbConnection->beginTransaction();

                $data['pen_name'] = isset($excel_datas[$i - 1][0]) ? $excel_datas[$i - 1][0] : '';
                $data['author_name'] = isset($excel_datas[$i - 1][1]) ? $excel_datas[$i - 1][1] : '';

                if (isset($excel_datas[$i - 1][2])) {
                    if ($excel_datas[$i - 1][2] == "男") {
                        $excel_datas[$i - 1][2] = "M";

                    } elseif ($excel_datas[$i - 1][2] == "女") {
                        $excel_datas[$i - 1][2] = "F";
                    } else {
                        $excel_datas[$i - 1][2] = 'N';
                    }
                } else {
                    $excel_datas[$i - 1][2] = 'N';
                }

                $data['gender'] = isset($excel_datas[$i - 1][2]) ? $excel_datas[$i - 1][2] : 'N';

                if (isset($excel_datas[$i - 1][3])) {
                    if($excel_datas[$i - 1][3]!=''){
                        $str = $excel_datas[$i - 1][3];
                        $str_sec = explode("/",$str);
                        $excel_datas[$i - 1][3] = $str_sec[2].'-'.$str_sec[1].'-'.$str_sec[0];
                        $birth_year = (string)$str_sec[2];

                        //echo '******';
                        $excel_datas[$i - 1][3] = date('Y-m-d',strtotime($excel_datas[$i - 1][3].' -1 day'));
                        //echo '******';
                       // echo '-----';
                       // echo date('Y/m/d',));
                        //$excel_datas[$i - 1][3] = date('Y/m/d',strtotime($excel_datas[$i - 1][3]));
                    } else {
                        //echo 'A2';
                        $excel_datas[$i - 1][3] = NULL;
                        $birth_year = NULL;
                    }
                } else {
                    //echo 'A3';
                    $excel_datas[$i - 1][3] = NULL;
                    $birth_year = NULL;
                }


                if (isset($excel_datas[$i - 1][4])) {
                    if($excel_datas[$i - 1][4]!=''){
                        $str = $excel_datas[$i - 1][4];
                        $str_sec = explode("/",$str);
                        $excel_datas[$i - 1][4] = $str_sec[2].'-'.$str_sec[1].'-'.$str_sec[0];
                        $excel_datas[$i - 1][4] = date('Y-m-d',strtotime($excel_datas[$i - 1][4].' -1 day'));
                    } else {
                        $excel_datas[$i - 1][4] = NULL;
                    }
                } else {
                    $excel_datas[$i - 1][4] = NULL;
                }

                $data['birth'] = isset($excel_datas[$i - 1][3]) ? $excel_datas[$i - 1][3] : '';
                $data['death'] = isset($excel_datas[$i - 1][4]) ? $excel_datas[$i - 1][4] : '';

                $data['office_address'] = isset($excel_datas[$i - 1][5]) ? $excel_datas[$i - 1][5] : '';
                $data['service'] = isset($excel_datas[$i - 1][6]) ? $excel_datas[$i - 1][6] : '';
                $data['job_title'] = isset($excel_datas[$i - 1][7]) ? $excel_datas[$i - 1][7] : '';
                $data['office_phone'] = isset($excel_datas[$i - 1][8]) ? $excel_datas[$i - 1][8] : '';
                $data['office_fax'] = isset($excel_datas[$i - 1][9]) ? $excel_datas[$i - 1][9] : '';

                if (isset($excel_datas[$i - 1][10])) {
                    if(!empty($excel_datas[$i - 1][10])){
                        $excel_datas[$i - 1][10] = $excel_datas[$i - 1][10];
                    } else {
                        $excel_datas[$i - 1][10] = '';
                    }
                } else {
                    $excel_datas[$i - 1][10] = '';
                }


                $data['email'] = isset($excel_datas[$i - 1][10]) ? $excel_datas[$i - 1][10] : '';
                $data['home_address'] = isset($excel_datas[$i - 1][11]) ? $excel_datas[$i - 1][11] : '';
                $data['home_phone'] = isset($excel_datas[$i - 1][12]) ? $excel_datas[$i - 1][12] : '';
                $data['home_fax'] = isset($excel_datas[$i - 1][13]) ? $excel_datas[$i - 1][13] : '';
                $data['mobile'] = isset($excel_datas[$i - 1][14]) ? $excel_datas[$i - 1][14] : '';


                if (isset($excel_datas[$i - 1][15])) {
                    if ($excel_datas[$i - 1][15] == "作家") {
                        $excel_datas[$i - 1][15] = "作家";
                    } elseif ($excel_datas[$i - 1][15] == "出版社") {
                        $excel_datas[$i - 1][15] = "出版社";
                    } elseif ($excel_datas[$i - 1][15] == "公部門") {
                        $excel_datas[$i - 1][15] = "公部門";
                    } elseif ($excel_datas[$i - 1][15] == "廠商") {
                        $excel_datas[$i - 1][15] = "廠商";
                    } elseif ($excel_datas[$i - 1][15] == "會員") {
                        $excel_datas[$i - 1][15] = "會員";
                    } elseif ($excel_datas[$i - 1][15] == "其他") {
                        $excel_datas[$i - 1][15] = "其他";
                    } else {
                        $excel_datas[$i - 1][15] = '未設定';
                    }
                } else {
                    $excel_datas[$i - 1][15] = '未設定';
                }

                $data['identity_type'] = isset($excel_datas[$i - 1][15]) ? $excel_datas[$i - 1][15] : '未設定';//**

                $data['social_account'] = isset($excel_datas[$i - 1][16]) ? $excel_datas[$i - 1][16] : '';
                $data['memo'] = isset($excel_datas[$i - 1][17]) ? $excel_datas[$i - 1][17] : '';
                $data['nationality'] = isset($excel_datas[$i - 1][18]) ? $excel_datas[$i - 1][18] : '';
                $data['identity_number'] = isset($excel_datas[$i - 1][19]) ? $excel_datas[$i - 1][19] : '';
                $data['residence_address'] = isset($excel_datas[$i - 1][20]) ? $excel_datas[$i - 1][20] : '';

                $data['bank_name'] = isset($excel_datas[$i - 1][21]) ? $excel_datas[$i - 1][21] : '';
                $data['bank_code'] = isset($excel_datas[$i - 1][22]) ? $excel_datas[$i - 1][22] : '';
                $data['branch_name'] = isset($excel_datas[$i - 1][23]) ? $excel_datas[$i - 1][23] : '';
                $data['branch_code'] = isset($excel_datas[$i - 1][24]) ? $excel_datas[$i - 1][24] : '';
                $data['bank_account'] = isset($excel_datas[$i - 1][25]) ? $excel_datas[$i - 1][25] : '';
                $data['account_name'] = isset($excel_datas[$i - 1][26]) ? $excel_datas[$i - 1][26] : '';

                $data['bank_name2'] = isset($excel_datas[$i - 1][27]) ? $excel_datas[$i - 1][27] : '';
                $data['bank_code2'] = isset($excel_datas[$i - 1][28]) ? $excel_datas[$i - 1][28] : '';
                $data['branch_name2'] = isset($excel_datas[$i - 1][29]) ? $excel_datas[$i - 1][29] : '';
                $data['branch_code2'] = isset($excel_datas[$i - 1][30]) ? $excel_datas[$i - 1][30] : '';
                $data['bank_account2'] = isset($excel_datas[$i - 1][31]) ? $excel_datas[$i - 1][31] : '';
                $data['account_name2'] = isset($excel_datas[$i - 1][32]) ? $excel_datas[$i - 1][32] : '';


                    $author->author_name = $data['author_name'];
                    $author->gender = $data['gender'];
                    $author->birth = (!empty($data['birth'])) ? $data['birth'] : null;
                    $author->death = (!empty($data['death'])) ? $data['death'] : null;
                    $author->job_title = $data['job_title'];
                    $author->service = $data['service'];
                    $author->identity_type = AuthorService::toJson('；', trim($data['identity_type']));
                    $author->nationality = $data['nationality'];
                    $author->residence_address = $data['residence_address'];
                    $author->office_address = AuthorService::toJson('；', trim($data['office_address']));
                    $author->office_phone = AuthorService::toJson('；', trim($data['office_phone']));
                    $author->office_fax = AuthorService::toJson('；', trim($data['office_fax']));
                    $author->email = AuthorService::toJson('；', trim($data['email']));
                    $author->home_address = AuthorService::toJson('；', trim($data['home_address']));
                    $author->home_phone = AuthorService::toJson('；', trim($data['home_phone']));
                    $author->home_fax = AuthorService::toJson('；', trim($data['home_fax']));
                    $author->mobile = AuthorService::toJson('；', trim($data['mobile']));
                    $author->social_account = AuthorService::toJson('；', trim($data['social_account']));
                    $author->memo = $data['memo'];
                    $author->identity_number = AuthorService::toJson('；', trim($data['identity_number']));
                    $author->pen_name = AuthorService::toJson('；', trim($data['pen_name']));
                    $author->create_at = $now;
                    $author->update_at = $now;
                    $author->birth_year = $birth_year;
                    $author->save();

                    $pk = Yii::app()->db->getLastInsertID();

                    if ($author->hasErrors()) {
                        echo $author->getErrors();
                        echo ' 第' . $i-1 . '行';
                        echo '\n';
                        Yii::log(date("Y-m-d H:i:s") .$author->getErrors(). '第' . $i . '行', CLogger::LEVEL_ERROR);

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

                    $transaction->commit();


                } catch (Exception $e) {
                    echo '第' . ($i+1) . '行::錯誤訊息'.$e->getMessage()."\n";
                    $transaction->rollback();
                    Yii::log(date("Y-m-d H:i:s") . $e->getMessage() . '第' . $i . '行', CLogger::LEVEL_ERROR);
                    continue;
                }

            }
        }


    }


    public static function toJson($split, $text)
    {
        if ($text === '' || $text === null) {
            return json_encode([]);
        }

        $data = explode($split, $text);

        foreach ($data as $k => $v) {
            $data[$k] = trim($v);
        }

        $r = json_encode($data);

        return $r;
    }

}