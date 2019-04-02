<link rel="stylesheet"
      href="<?php echo Yii::app()->request->baseUrl; ?>/assets/lightslider-master/src/css/lightslider.css"/>
<link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/lightslider-master/src/js/lightslider.js"></script>

<div id='main' class='container-fluid np_'>
  <!-- 上方logo 條-->
  <!--
  <div id='head_bar' class='col-md-12 col-sm-12 col-xs-12'>

    <div id='logo_box' class='col-md-2 col-sm-2 col-xs-2 text-center'>

      <div id='log_circle'>

        <img src="<?= Yii::app()->createUrl('/assets/site/images/index/logo320320.png'); ?>">

      </div>
    </div>
    <div id='isgt' class="col-md-0 col-sm-8  col-xs-8 p_ text-center">
      it's good time for everything.
    </div>
    <div id='pmenu' class='col-md-0 col-sm-2 col-sm-offset-0 col-xs-2 col-xs-offset-0 p_ text-right'>
      <div id='ctlmenu'>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>

    <div id='fuc_box' class='col-md-8 col-md-offset-2 col-sm-12 col-xs-12 text-center'>

      <?php
      if (isset(Yii::app()->session['member_id'])): ?>
        <a href="<?= Yii::app()->createUrl('/site/member_info'); ?>">
          <div class='col-md-2 col-sm-12 col-xs-12 text-center menubtn'>
            會員資料
          </div>
        </a>
        <a href="<?= Yii::app()->createUrl('/site/member_pass'); ?>">
          <div class='col-md-2 col-sm-12 col-xs-12 text-center menubtn'>
            密碼修改
          </div>
        </a>
        <a href="<?= Yii::app()->createUrl('/site/member_order'); ?>">
          <div class='col-md-2 col-sm-12 col-xs-12 text-center menubtn'>
            訂單查詢
          </div>
        </a>
        <a href="<?= Yii::app()->createUrl('/site/loginout'); ?>">
          <div class='col-md-2 col-sm-12 col-xs-12 text-center menubtn'>
            會員登出
          </div>
        </a>
      <?php else : ?>
        <a href="<?= Yii::app()->createUrl('/site/login'); ?>">
          <div class='col-md-2 col-sm-12 col-xs-12 text-center menubtn'>
            會員登入
          </div>
        </a>
      <?php endif; ?>


    </div>


  </div>-->




  <div id='memcontent' class='col-md-12 col-sm-12 col-xs-12 np_' >

    <div class='col-md-12 col-sm-12 col-xs-12 np_'>
      <!--<p>請輸入您的帳號密碼後登入..</p>-->


      <div class='col-md-12 col-sm-12 col-xs-12 np_'>

        <!-- 主要內容 -->
        <div id='goods_area' class='col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 np_'>
          <div class='col-md-12 col-sm-12 col-xs-12'>
            <!--
            <div class='p_ col-md-12 col-sm-12 col-xs-12'>
              <div id='breadcrumb'>
                <span class='prepath'><a href="<?php echo Yii::app()->createUrl('site/member_info');?>">會員資料</a></span>
                <span class='patharr'>></span>
                <span class='prepath'>會員專區</span>
                <span class='patharr'>></span>
                <span class='mainpath'>會員資料</span>
              </div>
            </div>
            -->
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

<form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('register/create_register');?>" method="post">
    <?php CsrfProtector::genHiddenField(); ?>
    <input type="hidden" class="form-control" id="status" name="status" placeholder="請輸入姓名" value="1">
    <div class="panel panel-default">
        <div class="panel-body">

          <div class="row">
            <div class="title-wrap col-lg-12">
              <h3 class="title-left">會員註冊</h3>
            </div>
          </div>
            
            <div class="form-group">
                <label for="name"  class="col-sm-3 control-label">使用者姓名:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" placeholder="請輸入姓名" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="name"  class="col-sm-3 control-label">使用者帳號:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="account" name="account" placeholder="請輸入帳號" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="mem_password" class="col-sm-3 control-label">密碼:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" placeholder="請輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirm" class="col-sm-3 control-label">確認密碼:</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password_confirm" placeholder="請再次輸入密碼">
                </div>
            </div>

            <div class="form-group">
                <label for="sex" class="col-sm-3 control-label">姓別:</label>
                <div class="col-sm-6">
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
                <label for="phone"  class="col-sm-3 control-label">行動電話1:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone1" placeholder="請輸入行動電話1" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-3 control-label">行動電話2:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone2" placeholder="請輸入行動電話2" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-3 control-label">電話1:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="tel_no1" name="tel_no1" placeholder="請輸入電話1" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="phone"  class="col-sm-3 control-label">電話2:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="tel_no2" name="tel_no2" placeholder="請輸入電話2" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="email"  class="col-sm-3 control-label">電子郵件1:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email1" name="email1" placeholder="請輸入電子郵件1" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="email"  class="col-sm-3 control-label">電子郵件2:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email2" name="email2" placeholder="請輸入電子郵件2" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">生日:</label>
                <div class="col-sm-6">
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
                <label for="address"  class="col-sm-3 control-label">卡號:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="請輸入卡號" value="">
                </div>
            </div>

            <div class="form-group">
                <label for="address"  class="col-sm-3 control-label">通訊地址:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="address" name="address" placeholder="請輸入通訊地址" value="">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">使用者分類:</label>
                <div class="col-sm-6">
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
                    <select class="form-control" id='level' name='level'>
                    <?php foreach ($groups as $groupk => $group): ?>
                        <option value="<?=$group->group_number?>"> <?=$group->group_name ?> </option>
                    <?php endforeach ?>
                    </select>
                </div>
            </div>
            <!--
            <div class="form-group">
                <label class="col-sm-3 control-label">使用者身份狀態:</label>
                <div class="col-sm-6">
                    <label class="radio-inline">
                        <input type="radio" name="level" value="1">學生
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="level" value="2">行政
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="level" value="3">教授
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="level" value="4">特殊
                    </label>
                </div>
            </div>
            -->

            <div class="form-group">
                <label class="col-sm-3 control-label">指導教授:</label>
                <div class="col-sm-6">
                    <select class="form-control" id='professor' name='professor'>
                        <option value='0'>-無指導教授-</option>
                    <?php foreach ($professor as $professor_key => $professor_val): ?>
                        <option value="<?=$professor_val->id?>"><?=$professor_val->name?></option>
                    <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-default">新增</button>
                </div>
            </div>

        </div>
    </div>
</form>
<script>
    $(function() {
        if ($('#success_msg').html() != '') {
           // $('#success_msg').show().fadeOut(2000)
        }
    })

    $(function() {
        if ($('#error_msg').html() != '') {
            //$('#error_msg').show().fadeOut(2000)
        }
    })

    // 使用者分類1改變時的動作
    $(function(){

        $("#grp1").change(function(){
            
            // 如果分類一的值不等於0,才接著做計算
            if( $("#grp1").val() != 0 ){
                
                // ajax傳送父分類的值去撈出所有子分類
                var request = $.ajax({
                    url: "<?=Yii::app()->createUrl('/register/getgrp2')?>",
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
        </div>
        <!-- 主要內容結束 -->
      </div>

    </div>


  </div>

  <script>
        $(function () {

        $("#pmenu").click(function () {

            $(this).toggleClass('toggle');
            $('#fuc_box').slideToggle();
            return false;

        });

        var errarr = '<?= json_encode(Yii::app()->session['error_msg']);?>';
        var tmperr = '';

        if (JSON.parse(errarr) != null) {

            $.each( JSON.parse(errarr), function( key, value ) {
                tmperr+=value+"<br>";
            });

            swal({
                type: 'error',
                title: 'Oops...',
                html: tmperr,
            })

            <?php
            unset(Yii::app()->session['error_msg']);
            ?>
        }


    })
  </script>
