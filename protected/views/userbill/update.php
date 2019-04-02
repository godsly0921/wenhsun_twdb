<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳號修改</h3>
    </div>
</div>

<div id="error_msg">
    <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                    <li><?= $error[0] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>


<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>

<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('member/update'); ?>"
      method="post">
    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="id" class="col-sm-2 control-label">系統編號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="id" name="id" readonly="readonly" placeholder="系統編號"
                           value="<?= $data->id ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">使用者姓名:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="請輸入姓名"
                           value="<?= $data->name ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">使用者帳號(身分證字號):</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="account" name="account" readonly="readonly"
                           placeholder="請輸入帳號" value="<?= $data->account ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-sm-2 control-label">姓別:</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="sex"
                               value="0" <?php echo ($data->sex == 0) ? 'checked="checked"' : '' ?>>女
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="sex"
                               value="1" <?php echo ($data->sex == 1) ? 'checked="checked"' : '' ?>>男
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="sex"
                               value="2" <?php echo ($data->sex == 2) ? 'checked="checked"' : '' ?>>尚未設定
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">行動電話1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="phone1" placeholder="請輸入行動電話1"
                           value="<?= $data->phone1 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">行動電話2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="phone2" placeholder="請輸入行動電話2"
                           value="<?= $data->phone2 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">電話1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="tel_no1" name="tel_no1" placeholder="請輸入電話1"
                           value="<?= $data->tel_no1 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">電話2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="tel_no2" name="tel_no2" placeholder="請輸入電話2"
                           value="<?= $data->tel_no2 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">電子郵件1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="email1" name="email1" placeholder="請輸入電子郵件1"
                           value="<?= $data->email1 ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">電子郵件2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="email2" name="email2" placeholder="請輸入電子郵件2"
                           value="<?= $data->email2 ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">生日:</label>
                <div class="col-sm-5">
                    <select name="year">
                        <option>西元年</option>
                        <?php
                        foreach ($years as $value): ?>
                            <?php if ($data->year == $value): ?>
                                <option selected="selected" value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <select name="month">
                        <option>月</option>
                        <?php
                        foreach ($months as $value): ?>
                            <?php if ($data->month == $value): ?>
                                <option selected="selected" value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <select name="day">
                        <option>日</option>
                        <?php
                        foreach ($days as $value): ?>
                            <?php if ($data->day == $value): ?>
                                <option selected="selected" value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="col-sm-2 control-label">卡號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="請輸入卡號"
                           value="<?= $data->card_number ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="col-sm-2 control-label">通訊地址:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="address" name="address" placeholder="請輸入通訊地址"
                           value="<?= $data->address ?>">
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">是否停卡(停權):</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="status"
                               value="0" <?php echo ($data->status == 0) ? 'checked="checked"' : '' ?>>否
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status"
                               value="1" <?php echo ($data->status == 1) ? 'checked="checked"' : '' ?>>是
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people" class="col-sm-2 control-label">停卡日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="stop_card_datetime" name="stop_card_datetime"
                           placeholder="停卡日期" value="<?= $data->stop_card_datetime ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people" class="col-sm-2 control-label">停卡原因:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="stop_card_remark"
                           placeholder="停卡原因" value="<?= $data->stop_card_remark ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">復卡人:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="canceler">
                        <?php foreach($accounts as $key=>$value): ?>
                            <?php if($data->stop_card_people == $value->id):?>
                                <option selected="selected" value="<?= $value->id ?>">
                                    <?= $value->account_name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $value->id ?>">
                                    <?= $value->account_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">使用者分類:</label>
                <div class="col-sm-5">
                    <select class="form-control" id='grp1' name='grp1'>
                        <?php foreach ($grp_data as $grp_key => $grp_val): ?>
                            <option value="<?= $grp_val->id ?>"
                                <?php
                                if ($grp_val->id == $data->grp_lv1) {
                                    echo 'selected';
                                }
                                ?>
                            ><?= $grp_val->name ?></option>
                        <?php endforeach ?>
                    </select>
                    <select class="form-control" id='grp2' name='grp2'>
                        <?php
                        if (count($grp_array) > 0) {
                            foreach ($grp_array as $grparrk => $grparrv) {
                                ?>
                                <option value="<?= $grparrv->id ?>"
                                    <?php
                                    if ($grparrv->id == $data->grp_lv2) {
                                        echo 'selected';
                                    }
                                    ?>
                                ><?= $grparrv->name ?></option>
                                <?php
                            }
                        } else {
                            ?>

                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">使用者身份狀態:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="user_group">
                        <?php foreach ($groups as $group): ?>
                            <?php if ($data->user_group == $group->group_number): ?>
                                <option selected="selected" value="<?= $group->group_number ?>">
                                    <?= $group->group_name ?>
                                </option>
                            <?php else: ?>
                                <option value="<?= $group->group_number ?>">
                                    <?= $group->group_name ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">指導教授:</label>
                <div class="col-sm-5">
                    <select class="form-control" id='professor' name='professor'>
                        <option value='0'>-無指導教授-</option>
                        <?php foreach ($professor as $professor_key => $professor_val): ?>
                            <option value="<?= $professor_val->id ?>"
                                <?php
                                if ($professor_val->id == $data->professor) {
                                    echo 'selected';
                                }
                                ?>
                            ><?= $professor_val->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people" class="col-sm-2 control-label">註冊日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="create_date" disabled="disabled" placeholder="註冊日期"
                           value="<?= $data->create_date ?>">
                </div>
            </div>


            <div class="form-group">
                <label for="recommend_people" class="col-sm-2 control-label">異動日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_date" name="edit_date" disabled="disabled"
                           placeholder="異動日期" value="<?= $data->edit_date ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
            </div>

        </div>
    </div>
</form>

<div class="panel panel-default">
    <div class="panel-heading">密碼修改</div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="<?php echo Yii::app()->createUrl('member/update'); ?>">
            <?php CsrfProtector::genHiddenField(); ?>
            <input type="hidden" name="id" value="<?= $data->id ?>">
            <input type="hidden" name="update_type" value="PW">
            <div class="form-group">
                <label for="mem_password" class="col-sm-2 control-label">密碼:</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" name="password" placeholder="請輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">確認密碼:</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" name="password_confirm" placeholder="請再次輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">密碼修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function () {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(2000)
        }
    })

    // 使用者分類1改變時的動作
    $(function () {

        $("#grp1").change(function () {

            // 如果分類一的值不等於0,才接著做計算
            if ($("#grp1").val() != 0) {

                // ajax傳送父分類的值去撈出所有子分類
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('/member/getgrp2')?>",
                    method: "POST",
                    data: {
                        grp1: $("#grp1").val()
                    },
                    dataType: "json"
                });

                request.done(function (msg) {

                    // 清空子分類
                    $("#grp2").empty();

                    // 填充子分類
                    $.each(msg, function (key, val) {
                        console.log(val);
                        $("#grp2").append("<option value=" + val['id'] + ">" + val['name'] + "</option>");
                    });


                });

                request.fail(function (jqXHR, textStatus) {
                    console.log("Request failed: " + textStatus);
                });

            }

        });
    })

</script>
<script>
    $(function () {
        $("#stop_card_datetime").datepicker({dateFormat: 'yy-mm-dd'}).val();
    });
</script>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>
