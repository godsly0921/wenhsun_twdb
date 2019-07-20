<style type="text/css">
    hr {
        border: 1px solid #827967;
    }

    .container {
        padding-top: 100px;
    }
    .login_title{
        color: #351d0f;
    }
    .register{
        color: #d65f30;
        text-decoration: underline;
    }
    .register:hover{
        color: #d65f30;
    }
    .forget{
        color: #d65f30;
    }
    .forget:hover{
        color: #d65f30;
        text-decoration: none;
    }
    .col-form-label{
        color: #666666;
        font-size: 18px;
        text-align: justify;　/*　Firefox到此即可對齊　*/
        text-justify: distribute-all-lines;
        text-align-last: justify;
    }
    .login_button{
        background-color: #db5523;
        color: #fff;
        border-radius: 30px;
    }
    #account,#password{
        border-color: #c8c5be;
        background-color: transparent;
    }

    img {
        padding-top: 16px;
        padding-bottom: 16px;
    }
</style>
<div class="container">
    <div class="text-center">
        <h3 class="login_title">會員登入</h3>
    </div>
    <hr>
    <a href="<?php echo Yii::app()->createUrl('site/register'); ?>" class="text-center register"><h6>還不是會員?立即註冊</h6></a>
    <div id="error_msg">
        <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== '') : ?>
            <div class="alert alert-danger">
                <?php echo Yii::app()->session['error_msg']; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="success_msg">
        <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== '') : ?>
            <p class="alert alert-success">
                <?php echo Yii::app()->session['success_msg']; ?>
            </p>
        <?php endif; ?>
    </div>
    <form role="form" class="col-lg-8 mx-auto mt-5" action="<?php echo Yii::app()->createUrl('site/login'); ?>" method="post" accept-charset="utf-8">
        <div class="form-group row">
            <label for="account" class="col-sm-2 col-form-label">帳號</label>
            <div class="col-sm-8">
                <input type="email" class="form-control" id="account" name="account" placeholder="account" required>
            </div>
            <label class="col-sm-2 col-form-label" style="color:red;font-size: 14px;">必填 *</label>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">密碼</label>
            <div class="col-sm-8">
                <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
            </div>
            <label class="col-sm-2 col-form-label" style="color:red;font-size: 14px;">必填 *</label>
        </div>
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <a href="<?php echo Yii::app()->createUrl('site/forget'); ?>" class="col-sm-8 forget"><h6>忘記密碼?</h6></a>
        </div>
        <div class="form-group col-lg-12 text-center">
            <button type="submit" class="btn col-sm-8 col-md-6 col-xl-4 col-lg-4 login_button btn-lg">登入</button>
        </div>
    </form>
    <hr class="mt-5">
    <div class="text-center">
        <h3 class="login_title">其他帳號登入</h3>
    </div>
    <div class="col-sm-8 col-md-6 col-xl-4 col-lg-4 mx-auto text-center">
        <a href="<?php echo $fb_loginurl ?>"><img class="w-100" src="<?php echo Yii::app()->createUrl('/'); ?>/assets/image/fb.jpg"></a>
    </div>
    <div class="col-sm-8 col-md-6 col-xl-4 col-lg-4 mx-auto text-center">
        <a href="<?php echo Yii::app()->createUrl('site/googlelogin'); ?>"><img class="w-100" src="<?php echo Yii::app()->createUrl('/'); ?>/assets/image/google.jpg"></a>
    </div>
</div>
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>