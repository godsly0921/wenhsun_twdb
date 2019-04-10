<body class="login">
<style>
    .login_content h1:after, .login_content h1:before {
        content: "";
        height: 1px;
        position: absolute;
        top: 10px;
        width: 10%;
    }
</style>
<div>
    <?php if (isset(Yii::app()->session['message']) && Yii::app()->session['message'] !== ''): ?>
        <div id="error_alert" class="alert alert-danger alert-dismissible fade in" role="alert">
            <?php echo Yii::app()->session['message'];?>
            <?php unset(Yii::app()->session['message']);?>
        </div>
    <?php endif; ?>
    <div class="login_wrapper">

        <div class="animate form login_form">
            <section class="login_content">
                <form role="form" action="<?php echo Yii::app()->createUrl('admin/auth'); ?>" method="post" accept-charset="utf-8">
                    <h1>文訊雜誌社人資管理系統</h1>

                    <div>
                        <input type="text" id="user_account" class="form-control" name="user_account" placeholder="Username" required="required" autofocus/>
                    </div>
                    <div>
                        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required="required" />
                    </div>
                    <div>
                        <select class="form-control" name="login_type">
                            <option value="1" selected="selected">使用者</option>
                            <option value="0">系統管理員</option>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">

                        <p class="change_link">
                            <a href="<?php echo Yii::app()->createUrl('/admin/register');?>" class="to_register">註冊會員</a>
                            <input style="float:none;" type="submit" id="login-btn" class="btn btn-default submit" value="登入"/>
                        </p>

                        <div class="clearfix"></div>
                        <br />
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<script>
    $(function() {
        if ($('#error_alert').html() != '') {
            $('#error_alert').show().fadeOut(2000);
        }
    });
</script>
</body>

