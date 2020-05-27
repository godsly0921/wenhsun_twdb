<script src="<?php echo Yii::app()->request->baseUrl.'/assets/ckeditor/all/ckeditor.js'; ?>"></script>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">條款內容管理</h3>
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
<div class="panel panel-default" style="width=100%; overflow-y:scroll;">
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>編號</th>
                <th>分類</th>
                <th>條款內容</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data as $key => $value){ ?>
                <tr class="gradeC" role="row">
                    <td><?=$value['policy_id']?></td>
                    <td><?=$policy_type[$value['type']]?></td>
                    <td><?=$value['policy_content']?></td>              
                    <td>
                        <a class="oprate-right" href="<?php echo Yii::app()->createUrl('website/policy_update/') ?>/<?= $value['policy_id'] ?>">
                            <i class="fa fa-pencil-square-o fa-lg"></i>
                        </a>
                        <a
                            class="oprate-right oprate-del" data-mem-id="<?= $value['policy_id'] ?>" data-mem-name="<?= $value['policy_id'] ?>"><i class="fa fa-times fa-lg"></i>
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