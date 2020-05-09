<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">API調用查詢</h3>
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
                <th>Name<br/>功能名稱</th>
                <th>API Token<br/>指令</th>
                <th>API Key<br/>金鑰</th>
                <th>Request<br/>用戶請求資訊</th>
                <!-- <th>respond</th> -->
                <th>Call Time<br/>呼叫時間</th>
                <th>Respond<br/>查看</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($data)){?>
                <?php foreach ($data as $key => $value) {?>
                    <tr>
                        <td><?=$value['log_format']?></td>
                        <td><?=$value['api_token']?></td>
                        <td><?=$value['api_key']?></td>
                        <td><?=$value['request']?></td>
                        <!-- <td><?#=$value['respond']?></td> -->
                        <td><?=$value['start_time']?></td>
                        <td>
                            <a class="oprate-right oprate-search" onclick="check_respond('<?= $value['id'] ?>')">
                                <i class="fa fa-search fa-lg" style="cursor: pointer">查看</i>
                            </a>
                        </td>
                    </tr>
                <?php }?> 
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.12/dist/sweetalert2.all.min.js"></script>
<style type="text/css" media="screen">
    .swal2-popup .swal2-title{
        text-align: left;
    }
    .swal2-popup{
        width: 80%;
    }
</style>
<script>
    $(function() {
        if ($('#hide_message').html() != '') {
            $('#hide_message').show().fadeOut(3500)
        }
    })
</script>
<script>
    function check_respond(id){
            $.ajax({
                url: "<?= Yii::app()->createUrl('/apimanage/check_respond') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(response) {
                    swal(
                        "<pre>"+JSON.stringify(response, undefined, 4)+"</pre>"
                    )
                },
                error: function(response) {
                    swal(
                        "<pre>"+JSON.stringify(response, undefined, 4)+"</pre>"
                    )
                }
            });
        }
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            },
            "order": [[ 5, "desc" ]]
        });

        
    });
</script>


