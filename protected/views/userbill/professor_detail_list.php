<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<script>
function bill_record_send(){
    var perfessor_id = $('#perfessor_id').val();
    var opening_balance = $('#opening_balance').val()
    var other_fee = $('#other_fee').val();
    var device_fee = $('#device_fee').val();
    var door_fee = $('#door_fee').val();
    var ending_balance = $('#ending_balance').val();
    var checkout_time = $('#checkout_time').val();
    var collection_refund = $('#collection_refund').val();
    $.ajax({
        url: "<?=Yii::app()->createUrl('/userbill/create_bill_record')?>",
        type: 'POST',
        data: {
            perfessor_id: perfessor_id,
            opening_balance: opening_balance,
            other_fee: other_fee,
            device_fee: device_fee,
            door_fee: door_fee,
            ending_balance: ending_balance,
            checkout_time: checkout_time,
            collection_refund: collection_refund,
        },
        error: function(xhr) {
            alert('Ajax request 發生錯誤');
        },
        success: function(response) {
            if(response) alert('新增成功');
            else alert('新增失敗');
            location.reload();
        }
    });
}
</script>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳單查詢</h3>
    </div>
</div>

<div id="error_msg">
    <?php if (isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
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
<?php
unset(Yii::app()->session['error_msg']);
unset(Yii::app()->session['success_msg']);
?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app() -> createUrl('userbill/search'); ?>"  method="POST" >

                    <div class="form-group">
                        <label class="col-sm-2 control-label">第一層單位:</label>
                        <select class="form-control" id='grp1' name='grp1'>
                            <option value="0" selected="selected">---請選擇---</option>
                            <?php foreach ($grp_data as $grp_key => $grp_val): ?>
                                <option value="<?=$grp_val->id?>"><?=$grp_val->name?></option>
                            <?php endforeach ?>
                        </select>

                        <label class="col-sm-2 control-label">第二層單位:</label>
                        <select class="form-control" id='grp2' name='grp2'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>

                        <label class="col-sm-2 control-label">教授選擇:</label>
                        <select class="form-control" id='grp3' name='grp3'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>
                        <label class="col-sm-4 control-label">查詢日期 ( 此日期前未結帳資料 ):</label>
                        <input type="text" class="form-control datepicker" name="date_end" placeholder="請選擇結束日期" >
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-default">查詢</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- 參數選擇結束 -->

        <div class="panel panel-default">

            <!--<div class="panel-heading col-md-12">
                <div class='col-md-2'>
                    <form class="form-horizontal" action="<?php #echo Yii::app()->createUrl('userbill/getexcelList');?>" method="post">
                        <button type="submit" class="btn btn-default">匯出excel</button>
                    </form>
                </div>
                <div class='col-md-2'>
                    <a href="<?#=Yii::app()->createUrl('userbill/printerList');?>"  target="_blank">
                        <button class="btn btn-default">列印</button>
                    </a>
                </div>
                <div class='col-md-2'>
                    <form action="<?#=Yii::app()->createUrl('userbill/allMemberBill');?>" method="post" target="_blank">
                        <input type="hidden" name="date_end" value="<?#= isset($_POST['date_end'])?$_POST['date_end']:'' ?>">
                        <input type="hidden" name="date_start" value="<?#= isset($_POST['date_end'])?$_POST['date_end']:'' ?>">
                        <input type="submit" class="btn btn-default" value="全部成員帳單"/>
                    </form>
                </div>                
                <div class='col-md-2 col-sm-4 col-xs-4'>
                </div>
            </div>-->

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>筆數</th>
                                <th>教授姓名</th>
                                <th>上期結帳日</th>
                                <th>上期餘額</th>
                                <th>本期收退款</th>
                                <th>本期其他金額</th>
                                <th>本期機台費</th>
                                <th>本期門禁費</th>
                                <th>本期餘額</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($result)!=0):?>
                            <?php foreach ($result as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?=$key+1?></td>
                                    <td><?=$value['perfessor_data']['grp_lv1']?><br><?=$value['perfessor_data']['grp_lv2']?><br><?=$value['perfessor_data']['professor_name']?></td>
                                    <td>
                                        <form class="form-horizontal" action="/chingda/userbill/allstudentexcel" method="post">
                                            <input type="hidden" name="grp_lv1" value="<?=$value['perfessor_data']['grp_lv1_id']?>"/>
                                            <input type="hidden" name="grp_lv2" value="<?=$value['perfessor_data']['grp_lv2_id']?>"/>
                                            <input type="hidden" name="perfessor_id" value="<?=$value['perfessor_data']['perfessor_id']?>"/>
                                            <input type="hidden" name="date_end" value="<?= $date_end ?>">
                                            <label><?=$value['bill_data']['last_checkout_time']?></label><br>
                                            <button type="submit" class="btn btn-default">匯出excel</button>
                                        </form>
                                    </td>
                                    <td><?=$value['bill_data']['receivableAmount']?></td>
                                    <td><?=$value['bill_data']['collection_refundAmount']?></td>
                                    <td><?=$value['bill_data']['other_fee']?></td>
                                    <td><?=$value['bill_data']['divAmount']?></td>
                                    <td><?=$value['bill_data']['doorAmount']?></td>
                                    <td><?=$value['bill_data']['totalAmount']?></td>
                                    <td>
                                        <?php if($value['bill_data']['totalAmount']<0) {?>
                                        <form class="form-horizontal" action="" method="post">
                                            <input type="hidden" id="perfessor_id" name="perfessor_id" value="<?=$value['perfessor_data']['perfessor_id']?>"/>
                                            <input type="hidden" id="opening_balance" name="opening_balance" value="<?=$value['bill_data']['receivableAmount']?>"/>
                                            <input type="hidden" id="other_fee" name="other_fee" value="<?=$value['bill_data']['other_fee']?>"/>
                                            <input type="hidden" id="device_fee" name="device_fee" value="<?=$value['bill_data']['divAmount']?>"/>
                                            <input type="hidden" id="door_fee" name="door_fee" value="<?=$value['bill_data']['doorAmount']?>"/>
                                            <input type="hidden" id="ending_balance" name="ending_balance" value="<?=$value['bill_data']['totalAmount']?>"/>
                                            <input type="hidden" id="checkout_time" name="checkout_time" value="<?=$date_end?>"/>
                                            <input type="hidden" id="collection_refund" name="collection_refund" value="<?=$value['bill_data']['collection_refundAmount']?>"/>
                                            <button type="button" class="btn btn-default" onclick="bill_record_send();">
                                            確定送出
                                            </button>
                                        <?php } ?>
                                            <!-- <button type="submit" class="btn btn-default">本期收退款新增</button> -->
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<!-- <script
    src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/datatables-responsive/dataTables.responsive.js"></script> -->
<script>
    var usr_grp_data = JSON.parse('<?=$usr_grp?>');
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何使用者資料"
            }
        } );
        $( ".datepicker").datepicker({ dateFormat: 'yy-mm-dd' }).val();
        $('#bill_record_Modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var perfessor_id = button.data('perfessor_id') // Extract info from data-* attributes
            var professor_name = button.data('professor_name');
            var opening_balance = button.data('opening_balance');
            var other_fee = button.data('other_fee');
            var device_fee = button.data('device_fee');
            var door_fee = button.data('door_fee');
            var ending_balance = button.data('ending_balance');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text(professor_name + ' - 本期收退款新增')
            modal.find('.modal-body #perfessor_id').val(perfessor_id)
            modal.find('.modal-body #opening_balance').val(opening_balance)
            modal.find('.modal-body #other_fee').val(other_fee)
            modal.find('.modal-body #device_fee').val(device_fee)
            modal.find('.modal-body #door_fee').val(door_fee)
            modal.find('.modal-body #ending_balance').val(ending_balance)
        })
        

        
    } );
    
    $('#other_fee_send').on('click', function () {
        //alert('test');return;
        var professor_id = $('#other_fee_professor_id').val();
        var fee_create_time = $('#fee_create_time').val();
        var fee_amount = $('#fee_amount').val();
        var other_fee_memo = $('#other_fee_memo').val();
        $.ajax({
            url: "<?=Yii::app()->createUrl('/userbill/create_other_fee')?>",
            type: 'POST',
            data: {
                professor_id: professor_id,
                fee_create_time: fee_create_time,
                fee_amount: fee_amount,
                other_fee_memo: other_fee_memo,
            },
            error: function(xhr) {
                alert('Ajax request 發生錯誤');
            },
            success: function(response) {
                $('#other_fee_Modal').modal('hide');
                if(response) alert('新增成功');
                else alert('新增失敗');
                $('#other_fee_professor_id').val(0);
                $('#fee_amount').val(0);
                $('#other_fee_memo').val('');
                location.reload();
            }
        });
    });
    
    $(".oprate-del").on('click', function () {
        var calculationfeeId = $(this).data("calculationfee-id");
        var answer = confirm("確定要刪除 (" + calculationfeeId + ") ?");
        if (answer == true) {
            var form = document.createElement("form");
            form.setAttribute('method', "post");
            form.setAttribute('action', "<?php echo Yii::app()->createUrl('calculationfee/delete') ?>");
            var input = document.createElement("input");
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', '_token');
            input.setAttribute('value', "<?php echo CsrfProtector::putToken(true); ?>");
            var idInput = document.createElement("input");
            idInput.setAttribute('type', 'hidden');
            idInput.setAttribute('name', 'id');
            idInput.setAttribute('value', calculationfeeId);
            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
    $("#other_fee_grp1").change(function(){
        var grp1 = $("#other_fee_grp1").val();
        if(grp1 !=0){
            // 清空子分類
            $("#other_fee_grp2").empty();
                // 填充子分類
            $("#other_fee_grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
            // 清空子分類
            $("#other_fee_professor_id").empty();
                // 填充子分類
            $("#other_fee_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
            $.each(usr_grp_data[grp1]['grp2'], function(grp1_key, grp1_value) {               
                $("#other_fee_grp2").append("<option value="+grp1_key+">"+grp1_value.grp2_name+"</option>");
                // })
            });
        }else{
            // 清空子分類
            $("#other_fee_grp2").empty();
                // 填充子分類
            $("#other_fee_grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
            // 清空子分類
            $("#other_fee_professor_id").empty();
                // 填充子分類
            $("#other_fee_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
        }       
    });
    $("#other_fee_grp2").change(function(){
        var grp1 = $("#other_fee_grp1").val();
        var grp2 = $("#other_fee_grp2").val();
        if(grp1 !=0 && grp2 !=0){
            // 清空子分類
            $("#other_fee_professor_id").empty();
                // 填充子分類
            $("#other_fee_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
            $.each(usr_grp_data[grp1]['grp2'][grp2]['professor'], function(grp2_key, grp2_value) {
                $("#other_fee_professor_id").append("<option value="+grp2_value.professor_id+">"+grp2_value.professor_name+"</option>");
            });
        }else{
            // 清空子分類
            $("#other_fee_professor_id").empty();
                // 填充子分類
            $("#other_fee_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
        }       
    });

    $("#grp1").change(function(){
        // 如果分類一的值不等於0,才接著做計算
        if( $("#grp1").val() != 0 ){

            // ajax傳送父分類的值去撈出所有子分類
            var request = $.ajax({
                url: "<?=Yii::app()->createUrl('/member/getgrp2')?>",
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
                $("#grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
                $.each( msg , function(key , val){
                    $("#grp2").append("<option value="+val['id']+">"+val['name']+"</option>");
                });


            });

            request.fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            });

        }

    });
    // 使用者分類1改變時的動作結束

    $("#grp2").change(function(){
        // 如果分類一的值不等於0,才接著做計算
        if( $("#grp2").val() != 0 ){


            // ajax傳送父分類的值去撈出所有子分類
            var request = $.ajax({
                url: "<?=Yii::app()->createUrl('/member/getgrp3')?>",
                method: "POST",
                data: {
                    grp2 :$("#grp2").val()
                },
                dataType: "json"
            });

            request.done(function( msg ) {

                // 清空子分類
                $("#grp3").empty();

                // 填充子分類
                $("#grp3").append("<option value='0' selected='selected'>---請選擇---</option>");
                $.each( msg , function(key , val){
                    $("#grp3").append("<option value="+val['id']+">"+val['name']+"</option>");
                });


            });

            request.fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            });

        }

    });
</script>