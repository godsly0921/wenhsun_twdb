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
                <div class="x_panel">
                    <div class="x_title">
                        <h2>新增員工資料</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form id="form" method="post" action="<?= Yii::app()->createUrl('/employee/management/create');?>" data-parsley-validate class="form-horizontal form-label-left" novalidate>

                            <?php CsrfProtector::genHiddenField(); ?>

                            <p>帳號設定</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_name">帳號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="user_name" name="user_name" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">電子郵件</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="email" id="email" name="email" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

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

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">角色</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="role" name="role">
                                        <?php if(!empty($roles)):?>
                                            <?php foreach ($roles as $role):?>
                                                <option value="<?=$role['group_number']?>"><?=$role['group_name']?></option>
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
                                    <input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">性別</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="M">男</option>
                                        <option value="F">女</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="birth">生日</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="birth" name="birth" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="person_id">身分證字號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="person_id" name="person_id" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nationality">國籍</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="nationality" name="nationality" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="onboard_date">到職日</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="date" id="onboard_date" name="onboard_date" class="form-control col-md-7 col-xs-12" required="required">
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
                                    <input type="text" id="address" name="address" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">手機</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="mobile" name="mobile" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">市話</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="phone" name="phone" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>員工資訊</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="door_card_num">門禁卡號</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="door_card_num" name="door_card_num" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">是否啟用</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="available" name="enable">
                                        <option value="Y">是</option>
                                        <option value="N">否</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">分機號碼</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="ext_num" name="ext_num">
                                        <option value="">請選擇</option>
                                        <?php foreach ($exts as $ext):?>
                                            <option value="<?=$ext['id']?>"><?=$ext['ext_number']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">座位號碼</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="seat_num" name="seat_num">
                                        <option value="">請選擇</option>
                                        <?php foreach ($seats as $seat):?>
                                            <option value="<?=$seat['id']?>"><?=$seat['seat_number']?>(<?=$seat['seat_name']?>)</option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">部門</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="department" name="department">
                                        <option value="">請選擇</option>
                                        <?php foreach ($departments as $department):?>
                                            <option value="<?=$department?>"><?=$department?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">職務</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="position" name="position" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position_type">正職/兼職</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select class="form-control" id="position_type" name="position_type">
                                        <option value="1" selected>正職</option>
                                        <option value="2">兼職</option>   
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="memo">備註</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="memo" name="memo" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <p>匯款資料</p>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_name">銀行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_name" name="bank_name" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_code">銀行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_code" name="bank_code" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_branch_name">分行名稱
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_branch_name" name="bank_branch_name" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_branch_code">分行代碼
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_branch_code" name="bank_branch_code" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account">帳號
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_account" name="bank_account" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bank_account_name">戶名
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="bank_account_name" name="bank_account_name" class="form-control col-md-7 col-xs-12">
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">新增</button>
                                    <a class="btn btn-default pull-right" href="<?= Yii::app()->createUrl('/employee/management');?>">返回</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/js/twzipcode.js"></script>
<script>
    $('#twzipcode').twzipcode(
        {
            css: ['form-control', 'form-control'],
            countyName: "country",
            districtName: "dist",
            zipcodeIntoDistrict: true
        }
    );
</script>