<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">圖片專欄管理列表</h3>
       <!-- --><?php /*if ($canCreate === true): */?>
            <a href="<?php echo Yii::app()->createUrl('website/piccolumn_new'); ?>" class="btn btn-success btn-right">新增圖片專欄</a>
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
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>編號</th>
                <th>圖片名稱</th>
                <th>小圖</th>
                <th>活動標題</th>  
                <th>活動日期</th>
                <th>活動時間</th>
                <th>活動地址</th>
                <th>發佈時間</th>
                <th>是否發佈</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($picColumn_date as $key => $value){ ?>
                <tr class="gradeC" role="row">
                    <td><?=$value['piccolumn_id']?></td>
                    <td><?=$value['title']?></td>
                    <td><img src="<?php echo Yii::app()->createUrl('/'); ?><?=$value['pic']?>" width="100%"></td>
                    <td><?=$value['title']?></td>
                    <td><?=$value['date_start']?> ~ <?=$value['date_end']?></td>
                    <td><?=$value['time_desc']?></td>
                    <td><?=$value['address']?></td>
                    <td><?=$value['publish_start']?> ~ <?=$value['publish_end']?></td>
                    <td><?=$value['status'] == 1 ? "是":"否"?></td>
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('website/piccolumn_update/') ?>/<?= $value['piccolumn_id'] ?>">
                            <i class="fa fa-pencil-square-o fa-lg"></i>
                        </a>
                        <a
                            class="oprate-right oprate-del" data-mem-id="<?= $value['piccolumn_id'] ?>" data-mem-name="<?= $value['piccolumn_id'] ?>"><i class="fa fa-times fa-lg"></i>
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
        } );
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
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('website/piccolumn_delete') ?>");
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