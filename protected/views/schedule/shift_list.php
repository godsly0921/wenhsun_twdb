<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">班表設定</h3>
       <!-- --><?php /*if ($canCreate === true): */?>
            <a href="<?php echo Yii::app()->createUrl('schedule/shift_create'); ?>" class="btn btn-success btn-right">新增班別</a>
            <a href="<?php echo Yii::app()->createUrl('schedule/active_create'); ?>" class="btn btn-success btn-right">新增大活動備註</a>
       <!-- --><?php /*endif;*/?>
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
<strong><?=Yii::app()->session['success_msg'];?></strong>
</div>
<?php endif; ?>

<?php
unset( Yii::app()->session['error_msg'] );
unset( Yii::app()->session['success_msg'] );
?>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-heading">班別管理</div>
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>編號</th>
                <th>館別</th>
                <th>場別</th>  
                <th>班別</th>
                <th>是否為特殊上班時間</th>
                <th>上班時間</th>
                <th>下班時間</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($shift as $key => $value){ ?>
                <tr class="gradeC" role="row">
                    <td><?=$value['shift_id']?></td>
                    <td><?=$value['store_id'] == 1 ?"一般館舍":"茶館"?></td>
                    <td>
                        <?php if($value['in_out'] == 0 ) echo "不分"?>
                        <?php if($value['in_out'] == 1 ) echo "內場";?>
                        <?php if($value['in_out'] == 2 ) echo "外場"?>
                    </td>
                    <td><?=$value['class']?></td>
                    <td><?=$value['is_special'] == 0 ?"否":"是"?></td>
                    <td><?=$value['start_time']?></td>
                    <td><?=$value['end_time']?></td>
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('schedule/shift_update/') ?>/<?= $value['shift_id'] ?>">
                            <i class="fa fa-pencil-square-o fa-lg"></i>
                        </a>
                        <a class="oprate-right oprate-del" data-mem-id="<?= $value['shift_id'] ?>" data-mem-name="<?= $value['shift_id'] ?>"><i class="fa fa-times fa-lg"></i>
                        </a>
                    </td>
               </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-heading">大活動備註管理</div>
    <div class="panel-body">
        <table id="activeTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>編號</th>
                <th>活動名稱</th>
                <th>活動日期</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($active as $key => $value){ ?>
                <tr class="gradeC" role="row">
                    <td><?=$value['active_id']?></td>
                    <td><?=$value['active_name']?></td>
                    <td><?=$value['active_date']?></td>
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('schedule/active_update/') ?>/<?= $value['active_id'] ?>">
                            <i class="fa fa-pencil-square-o fa-lg"></i>
                        </a>
                        <a class="oprate-right active-del" data-mem-id="<?= $value['active_id'] ?>" data-mem-name="<?= $value['active_id'] ?>"><i class="fa fa-times fa-lg"></i>
                        </a>
                    </td>
               </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
        $('#activeTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    } );
</script>
<script>
    $(".oprate-del").on('click', function(){
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('schedule/shift_delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name' , 'id');
            idInput.setAttribute('value', id);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
    $(".active-del").on('click', function(){
        var id = $(this).data("mem-id");
        var memName = $(this).data("mem-name");
        var answer = confirm("確定要刪除 (" + memName + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method',"POST");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('schedule/active_delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name' , 'id');
            idInput.setAttribute('value', id);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>