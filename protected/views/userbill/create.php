<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳號新增</h3>
    </div>
</div>

<div id="error_msg">
    <?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
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

<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('member/create');?>" method="post">
    <?php CsrfProtector::genHiddenField(); ?>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="form-group">
                <label for="name"  class="col-sm-2 control-label">使用者姓名:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="name" name="name" placeholder="請輸入姓名" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name"  class="col-sm-2 control-label">使用者帳號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="account" name="account" placeholder="請輸入帳號" value="">
                </div>
            </div>

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
                <label for="sex" class="col-sm-2 control-label">姓別:</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="sex" value="0">女
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="sex" value="1">男
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="sex" value="2">尚未設定
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-2 control-label">行動電話1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="phone1" placeholder="請輸入行動電話1" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-2 control-label">行動電話2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="phone2" placeholder="請輸入行動電話2" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-2 control-label">電話1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="tel_no1" name="tel_no1" placeholder="請輸入電話1" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-2 control-label">電話2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="tel_no2" name="tel_no2" placeholder="請輸入電話2" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="email"  class="col-sm-2 control-label">電子郵件1:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="email1" name="email1" placeholder="請輸入電子郵件1" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="email"  class="col-sm-2 control-label">電子郵件2:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="email2" name="email2" placeholder="請輸入電子郵件2" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">生日:</label>
                <div class="col-sm-5">
                    <select name="year">
                        <option>西元年</option>
                        <?php
                        foreach($years as $value): ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="month">
                        <option>月</option>
                        <?php
                        foreach($months as $value): ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="day">
                        <option>日</option>
                        <?php
                        foreach($days as $value): ?>
                                <option value="<?= $value ?>">
                                    <?= $value ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="address"  class="col-sm-2 control-label">卡號:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="請輸入卡號" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="address"  class="col-sm-2 control-label">通訊地址:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="address" name="address" placeholder="請輸入通訊地址" value="">
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label">是否停卡(停權)::</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" checked="checked">否
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status"  value="1">是
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people" class="col-sm-2 control-label">停卡日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="stop_card_datetime" name="stop_card_datetime"
                           placeholder="停卡日期" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people" class="col-sm-2 control-label">停卡原因:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" name="stop_card_remark"
                           placeholder="停卡原因" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">復卡人:</label>
                <div class="col-sm-5">
                    <select class="form-control" name="canceler">
                        <option value="0" selected="selected">--無--</option>
                        <?php foreach($accounts as $key=>$value): ?>
                                <option value="<?= $value->id ?>">
                                    <?= $value->account_name ?>
                                </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">使用者分類:</label>
                <div class="col-sm-5">
                    <select class="form-control" id='grp1' name='grp1'>
                      <option value=0 >-請選擇第一層分類-</option>
                    <?php foreach ($grp_data as $grp_key => $grp_val): ?>
                       <option value="<?=$grp_val->id?>"><?=$grp_val->name?></option>
                    <?php endforeach ?>
                    </select>
                    <select class="form-control" id='grp2' name='grp2'>
                      <option>-請先選擇第一層分類-</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">使用者身份狀態:</label>
                <div class="col-sm-5">
                    <label class="radio-inline">
                        <input type="radio" name="level" value="student">學生
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="level" value="professor">行政
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="level" value="admin">教授
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="level" value="vip">特殊
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">指導教授:</label>
                <div class="col-sm-5">
                    <select class="form-control" id='professor' name='professor'>
                        <option value='0'>-無指導教授-</option>
                    <?php foreach ($professor as $professor_key => $professor_val): ?>
                        <option value="<?=$professor_val->id?>"><?=$professor_val->name?></option>
                    <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people"  class="col-sm-2 control-label">註冊日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control"  name="create_date" disabled="disabled" placeholder="註冊日期" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="recommend_people"  class="col-sm-2 control-label">異動日期:</label>
                <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_date" name="edit_date" disabled="disabled" placeholder="異動日期" value="">
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
<script>
    $(function() {
        if ($('#success_msg').html() != '') {
            $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function() {
        if ($('#error_msg').html() != '') {
            $('#error_msg').show().fadeOut(2000)
        }
    })

    // 使用者分類1改變時的動作
    $(function(){

        $("#grp1").change(function(){
            
            // 如果分類一的值不等於0,才接著做計算
            if( $("#grp1").val() != 0 ){
                
                // ajax傳送父分類的值去撈出所有子分類
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('/member/getgrp2')?>",
                    method: "POST",
                    data: { 
                        grp1 :$("#grp1").val()  
                    },
                    dataType: "json"
                });
 
                request.done(function( msg ) {
                    
                    // 清空子分類
                    $("#grp2").empty();
                    
                    // 填充子分類
                    $.each( msg , function(key , val){
                        console.log(val);
                        $("#grp2").append("<option value="+val['id']+">"+val['name']+"</option>");
                    });


                });
 
                request.fail(function( jqXHR, textStatus ) {
                    console.log( "Request failed: " + textStatus );
                });

            }else{
                
                $("#grp2").empty();
                $("#grp2").append("<option>-請先選擇第一層分類-</option>");
                
            }

        });
    })
    // 使用者分類1改變時的動作結束    
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
