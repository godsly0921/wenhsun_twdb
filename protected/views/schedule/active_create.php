<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<style type="text/css">
    .form-check-inline{
        display: inline-block;
        padding-top: 8px;
        padding-right: 5px;
    }
</style>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">大活動備註管理</h3>
    </div>
</div>
<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                <li><?= $error[0] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
<div class="alert alert-success">
<strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<div class="panel panel-default">
    <div class="panel-heading">大活動備註管理</div>
    <div class="panel-body">
        <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('schedule/active_create');?>" method="post" enctype="multipart/form-data">
            <?php CsrfProtector::genHiddenField(); ?>
            <div class="form-group row">
                <label class="col-sm-2 control-label">活動名稱:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="active_name" name="active_name" required placeholder="請輸入標題" value="">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 control-label">活動時間:</label>
                <div class="col-sm-10">
                    <input id="active_date" type="text" class="form-control active_date" name="active_date" value="" />
                    
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary col-sm-12">新增</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<!-- jQuery Tags Input -->
<!-- <script src="<?php #echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script> -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->    
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        
        // if(typeof $.fn.tagsInput !== 'undefined'){        
        //     $('#active_date').tagsInput({
        //       width: 'auto'
        //     }); 
        //   }
        $('#active_date').datetimepicker({
            format: "YYYY-MM-D"
        });
        // $('#active_date_tag').on('dp.change', function(e){ $('#active_date').tagsinput('add', dateText);})    
    })
</script>