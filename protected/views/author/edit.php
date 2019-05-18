<div role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (!empty(Yii::app()->session[Controller::ERR_MSG_KEY])): ?>
                    <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::ERR_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::ERR_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <?php if (!empty(Yii::app()->session[Controller::SUCCESS_MSG_KEY])): ?>
                    <div id="succ-alert" class="alert alert-success alert-dismissible fade in" role="alert">
                        <?php echo Yii::app()->session[Controller::SUCCESS_MSG_KEY];?>
                        <?php unset(Yii::app()->session[Controller::SUCCESS_MSG_KEY]);?>
                    </div>
                <?php endif; ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>作家編輯</h2><button id="delete-btn" class="btn btn-danger pull-right">刪除</button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="demo-form2" method="post" action="<?= Yii::app()->createUrl('/author/update');?>" data-parsley-validate class="form-horizontal form-label-left">
                            <p>基本資料</p>
                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" value="<?=$data->id?>" name="author_id" id="author_id">
                            <input type="hidden" value="<?=$bank_list[0]->id?>" name="author_bank_id">
                            <input type="hidden" value="<?=$bank_list[1]->id?>" name="author_bank_id_2">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">筆名
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="pen_name" name="pen_name"><?=$data->pen_name?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="author_name">姓名<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->author_name?>" id="author_name" name="author_name" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">性別</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="gender" name="gender">
                                        <option <?php if($data->gender === \Wenhsun\Enum\Gender::NONE):?>selected<?php endif;?> value="N">未設定</option>
                                        <option <?php if($data->gender === \Wenhsun\Enum\Gender::MALE):?>selected<?php endif;?> value="M">男</option>
                                        <option <?php if($data->gender === \Wenhsun\Enum\Gender::FEMALE):?>selected<?php endif;?> value="F">女</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="birth">生日
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" value="<?=$data->birth?>" id="birth" name="birth" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="death">卒日
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" value="<?=$data->death?>" id="death" name="death" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">電子郵件
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="email" name="email"><?=$data->email?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">公司 郵遞區號/地址
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="office_address" name="office_address"><?=$data->office_address?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="service">服務單位
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->service?>" id="service" name="service" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="job_title">職稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->job_title?>" id="job_title" name="job_title" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">辦公電話
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="office_phone" name="office_phone"><?=$data->office_phone?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">辦公傳真
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="office_fax" name="office_fax"><?=$data->office_fax?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">住家 郵遞區號/地址
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="home_address" name="home_address"><?=$data->home_address?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">住家電話
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="home_phone" name="home_phone"><?=$data->home_phone?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">住家傳真
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="home_fax" name="home_fax"><?=$data->home_fax?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">手機
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="mobile" name="mobile"><?=$data->mobile?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">身份類型</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select id="identity_type" class="select2_multiple form-control" multiple="multiple" name="identity_type[]">
                                        <option <?php if(in_array("未設定", $data->identity_type)):?>selected<?php endif;?> value="未設定">未設定</option>
                                        <option <?php if(in_array("作家", $data->identity_type)):?>selected<?php endif;?> value="作家">作家</option>
                                        <option <?php if(in_array("出版社", $data->identity_type)):?>selected<?php endif;?> value="出版社">出版社</option>
                                        <option <?php if(in_array("公部門", $data->identity_type)):?>selected<?php endif;?> value="公部門">公部門</option>
                                        <option <?php if(in_array("廠商", $data->identity_type)):?>selected<?php endif;?> value="廠商">廠商</option>
                                        <option <?php if(in_array("會員", $data->identity_type)):?>selected<?php endif;?> value="會員">會員</option>
                                        <option <?php if(in_array("其他", $data->identity_type)):?>selected<?php endif;?> value="其他">其他</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">網路社群帳號
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="social_account" name="social_account"><?=$data->social_account?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">備註
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="memo" name="memo"><?=$data->memo?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nationality">國籍
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->nationality?>" id="nationality" name="nationality" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">身分證字號/護照號碼/統一編號
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" rows="3" id="identity_number" name="identity_number"><?=$data->identity_number?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="residence_address">戶籍地
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->residence_address?>" id="residence_address" name="residence_address" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>銀行資料-1</p>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">銀行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[0]->bank_name?>" id="bank_name" name="bank_name" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_code">銀行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[0]->bank_code?>" id="bank_code" name="bank_code" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="branch_name">分行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[0]->branch_name?>" id="branch_name" name="branch_name" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="branch_code">分行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[0]->branch_code?>" id="branch_code" name="branch_code" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account">帳號
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[0]->bank_account?>" id="bank_account" name="bank_account" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_name">戶名
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[0]->account_name?>" id="account_name" name="account_name" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>銀行資料-2</p>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name2">銀行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[1]->bank_name?>" id="bank_name2" name="bank_name2" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_code2">銀行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[1]->bank_code?>" id="bank_code2" name="bank_code2" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="branch_name2">分行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[1]->branch_name?>" id="branch_name2" name="branch_name2" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="branch_code2">分行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[1]->branch_code?>" id="branch_code2" name="branch_code2" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account2">帳號
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[1]->bank_account?>" id="bank_account2" name="bank_account2" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="account_name2">戶名
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$bank_list[1]->account_name?>" id="account_name2" name="account_name2" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="modify-btn" class="btn btn-primary">修改</button>
                                    <a class="btn btn-default pull-right" href="/author">返回</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        $("#delete-btn").click(function(){
            let r = confirm("確認要刪除資料?");
            if (r === true) {
                var token = $("#_token").prop("value");
                var id = $("#author_id").prop("value");
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('author/delete'); ?>",
                    method: "POST",
                    data: {"id":id, "_token":token},
                    dataType: "json"
                });

                request.done(function(data) {
                    location.href = "<?=Yii::app()->createUrl('author'); ?>";
                });

                request.fail(function(jqXHR, textStatus) {
                    alert(jqXHR.responseJSON.message);
                });
            }
        });
    });
</script>