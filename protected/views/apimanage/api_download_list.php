<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">API 下載管理</h3>
    </div>
</div>
<?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
    <div class="alert alert-danger">
        <?=Yii::app()->session['error_msg']?>
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
<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?>
<div class="panel panel-default" style="width=100%; overflow-y:scroll;"> 
    <div class="panel-body">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>圖片</th>
                <th>圖片編號</th>
                <th>尺寸</th>
                <th>API KEY</th>
                <th>下載時間</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($data)){?>
                <?php foreach ($data as $key => $value) {?>
                    <tr>
                        <td>
                            <img src="<?php echo Yii::app()->createUrl('/'); ?>/image_storage/P/<?=$value['image_id']?>.jpg">
                        </td>
                        <td><?=$value['image_id']?></td>
                        <td><?=$value['size_type']?></td>
                        <td><?=$value['api_key']?></td>
                        <td><?=$value['createtime']?></td>
                    </tr>
                <?php }?> 
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script>
    $(function() {
        if ($('#hide_message').html() != '') {
            $('#hide_message').show().fadeOut(3500)
        }
    })
</script>
<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            },
            "order": [[ 4, "desc" ]]
        });
    });
</script>


