<div role="main">
    <div>
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
                        <h2>修改員工資料</h2><button id="delete-btn" class="btn btn-danger pull-right">刪除</button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?= Yii::app()->createUrl('/employee/management/update');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" id="id" name="id" value="<?=$data->id?>">
                            <p>帳號設定</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_name">帳號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="<?=$data->user_name?>" required="required" disabled class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">電子郵件</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="email" id="email" name="email" value="<?=$data->email?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">角色</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="role" name="role">
                                        <?php if(!empty($roles)):?>
                                            <?php foreach ($roles as $role):?>
                                                <?php if($role['id'] === $data->role):?>
                                                    <option value="<?=$role['id']?>" selected><?=$role['group_name']?></option>
                                                <?php else:?>
                                                    <option value="<?=$role['id']?>"><?=$role['group_name']?></option>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        <?php else:?>
                                            <option value="">無可用角色</option>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>基本資料</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">姓名</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="name" name="name" value="<?=$data->name?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">性別</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="M" <?php if($data->gender === "M"): ?>selected<?php endif;?> >男</option>
                                        <option value="F" <?php if($data->gender === "F"): ?>selected<?php endif;?> >女</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="birth">生日</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="birth" name="birth" value="<?=$data->birth?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="person_id">身分證字號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="person_id" name="person_id" value="<?=$data->person_id?>" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nationality">國籍</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="nationality" name="nationality" value="<?=$data->nationality?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>聯絡資訊</p>

                            <div id="twzipcode">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">縣市</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" data-role="county"></div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">鄉鎮</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12" data-role="district"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">地址</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="address" name="address" value="<?=$data->address?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">手機</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="mobile" name="mobile" value="<?=$data->mobile?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">市話</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="phone" name="phone" value="<?=$data->phone?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>員工資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="door_card_num">門禁卡號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="door_card_num" name="door_card_num" value="<?=$data->door_card_num?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">是否啟用</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="available" name="enable">
                                        <option value="Y" <?php if($data->gender === "Y"): ?>selected<?php endif;?>>是</option>
                                        <option value="N" <?php if($data->gender === "N"): ?>selected<?php endif;?>>否</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">分機號碼</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="ext_num" name="ext_num">
                                        <?php if(!empty($exts)):?>
                                            <?php foreach ($exts as $ext):?>
                                                <option value="<?=$ext['id']?>" <?php if($ext['id'] === $data->ext->id):?>selected<?php endif;?>><?=$ext['ext_number']?></option>
                                            <?php endforeach;?>
                                        <?php else:?>
                                            <option value="">無可用分機號碼</option>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">座位號碼</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="seat_num" name="seat_num">
                                        <?php if(!empty($seats)):?>
                                            <?php foreach ($seats as $seat):?>
                                                <option value="<?=$seat['id']?>" <?php if($seat['id'] === $data->seat->id):?>selected<?php endif;?>><?=$seat['seat_number']?></option>
                                            <?php endforeach;?>
                                        <?php else:?>
                                            <option value="">無可用座位</option>
                                        <?php endif;?>
                                    </select>
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>匯款資料</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">銀行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_name" name="bank_name" value="<?=$data->bank_name?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_code">銀行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_code" name="bank_code" value="<?=$data->bank_code?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_branch_name">分行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_branch_name" name="bank_branch_name" value="<?=$data->bank_branch_name?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_branch_code">分行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_branch_code" name="bank_branch_code" value="<?=$data->bank_branch_code?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account">帳號
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_account" name="bank_account" value="<?=$data->bank_account?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account_name">戶名
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_account_name" name="bank_account_name" value="<?=$data->bank_account_name?>" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">修改</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>修改密碼</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="password_form" method="post" action="<?= Yii::app()->createUrl('/employee/management/updatepassword');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>
                            <?php CsrfProtector::genHiddenField(); ?>
                            <input type="hidden" name="id" value="<?=$data->id?>">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">密碼</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="password" id="password" name="password" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirm">密碼確認</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="password" id="password_confirm" name="password_confirm" data-parsley-equalto="#password" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">修改</button>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/js/twzipcode.js"></script>
<script>
    $(function(){

        $('#twzipcode').twzipcode(
            {
                css: ['form-control', 'form-control'],
                countyName: "country",
                districtName: "dist",
                zipcodeIntoDistrict: true,
                countySel: "<?=$data->country?>",
                districtSel: "<?=$data->dist?>"
            }
        );

        $("#delete-btn").click(function(){
            let r = confirm("確認要刪除資料?");
            if (r === true) {
                var token = $("#_token").prop("value");
                var id = $("#id").prop("value");
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('employee/management/delete'); ?>",
                    method: "POST",
                    data: {"id":id, "_token":token},
                    dataType: "json"
                });

                request.done(function(data) {
                    location.href = "<?=Yii::app()->createUrl('employee/management'); ?>";
                });

                request.fail(function(jqXHR, textStatus) {
                    alert(jqXHR.responseJSON.message);
                });
            }
        });

    });
</script>