<!--
<script>
	$(function() {
		if ($('#success_msg').html() != '') {
			$('#success_msg').show().fadeOut(3500);
		}
	})
</script>

<div id="success_msg">
    <?php if (isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
        <p class="alert alert-success">
            <?php echo Yii::app()->session['success_msg']; ?>
        </p>
    <?php endif; ?>
</div>
<div class="container">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <div class="login-panel panel panel-default">
                    <div class="panel-heading">

                        <h3 class="panel-title">文訊雜誌社人資管理系統</h3>

                    </div>

                    <div class="panel-body">

                    	<form role="form" action="<?php echo Yii::app()->createUrl('admin/auth'); ?>" method="post" accept-charset="utf-8">
                            <fieldset>

                            	<div id="hide_message">
                    					<p class="text-danger"><?php echo (isset(Yii::app()->session['message']) && Yii::app()->session['message'] != '') ? Yii::app()->session['message'] : ''; ?></p>
								</div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Account" name="user_account" type="text" autofocus value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>

                                <div class="form-group">
                                    <select class="form-control" name="login_type">
                                                <option selected="selected" value="1">使用者</option>
                                                <option value="0">系統管理員</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-6  text-left">
                                    <label>
                                        <a href="<?php echo Yii::app()->createUrl('/admin/register');?>" class="join" name="join" >註冊會員</a>
                                    </label>
                                </div>
                                <div class="col-md-6 checkbox text-right">
                                    <label>
                                        <input name="remember" type="checkbox" value="1">記住使用者
                                    </label>
                                </div>

                                <input type="submit" class="btn btn-lg btn-success btn-block"value="登入"/>

                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
-->