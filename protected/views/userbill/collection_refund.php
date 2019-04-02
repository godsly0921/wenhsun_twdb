<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">收退款及其他金額(資料查詢)</h3>
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
                <form action="<?php echo Yii::app() -> createUrl('userbill/collection_refund'); ?>"  method="POST" >

                    <div class="form-group">
                        <label class="col-sm-2 control-label">第一層單位:</label>
                        <select class="form-control" id='grp1' name='grp1'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>

                        <label class="col-sm-2 control-label">第二層單位:</label>
                        <select class="form-control" id='grp2' name='grp2'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>

                        <label class="col-sm-2 control-label">教授選擇:</label>
                        <select class="form-control" id='grp3' name='grp3'>
                            <option value="0" selected="selected">---請選擇---</option>
                        </select>
                        <label class="col-sm-4 control-label">查詢結帳日期(起):</label>
                        <input type="text" class="form-control datepicker" name="date_start" placeholder="請選擇結帳日期(起)" >
                        <label class="col-sm-4 control-label">查詢結帳日期(迄):</label>
                        <input type="text" class="form-control datepicker" name="date_end" placeholder="請選擇結帳日期(迄)" >
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-default">查詢</button>
                            <button type="button" class="btn btn-default" style="margin-left:20px;" data-toggle="modal" data-target="#collection_refund_Modal">收退款新增</button>
                            <button type="button" class="btn btn-default" style="margin-left:20px;" data-toggle="modal" data-target="#other_fee_Modal">其他金額新增</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- 參數選擇結束 -->
        <!-- 收退款及其他金額記錄(查詢結果) start -->
        <div class="panel panel-default">
            <div class="panel-body">
            <div class="row">
                <div class="title-wrap col-lg-12">
                    <h3 class="title-left">收退款及其他金額記錄(查詢結果)</h3>
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>筆數</th>
                                <th>日期</th>
                                <th>教授姓名</th>
                                <th>分類</th>
                                <th>收退款金額</th>                                
                                <th>其他金額</th>
                                <th>摘要</th>
                                <th>結帳時間</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($result)!=0):?>
                            <?php 
                            $i=0;
                            foreach ($result as $professor_id => $professor_data): ?>
                                <?php foreach ($professor_data as $key => $value): $i++?>
                                    <tr class="gradeC" role="row">
                                        <td><?=$i?></td>
                                        <td><?=$value['createtime']?></td>
                                        <td><?=$value['grp1_name']?><br><?=$value['grp2_name']?><br><?=$value['professor_name']?></td>                   
                                        <td><?=$value['bill_type']?></td>
                                        <td><?=$value['collection_refund_amount']?></td>
                                        <td><?=$value['other_fee_amount']?></td>
                                        <td><?=$value['memo']?></td>
                                        <td><?=$value['checkout_time']?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <!-- 收退款及其他金額記錄(查詢結果) end -->
    </div>
</div>
<div class="modal fade" id="collection_refund_Modal" tabindex="-1" role="dialog" aria-labelledby="collection_refund_ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="display:flex">
        <h5 class="modal-title" id="collection_refund_ModalLabel">收退款新增</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:auto;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <form class="form-horizontal" action="/chingda/userbill/create_other_fee" method="post">          -->
            <div class="form-group">
                <label for="message-text" class="col-form-label">第一層單位</label>
                <select class="form-control" id='collection_refund_grp1' name='grp1'>
                    <option value="0" selected="selected">---請選擇---</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">第二層單位</label>
                <select class="form-control" id='collection_refund_grp2' name='grp2'>
                    <option value="0" selected="selected">---請選擇---</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">教授選擇:</label>
                <select class="form-control" id="collection_refund_professor_id" name='professor_id'>
                    <option value="0" selected="selected">---請選擇---</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">收退款金額：</label>
                <input class="form-control" id="collection_refund_amount" name="collection_refund_amount"/>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">帳單屬性：</label>
                <select class="form-control" id="collection_or_refund">
                    <option name="collection_or_refund" value="1">收款</option>
                    <option name="collection_or_refund" value="0">退款</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">收退款方式：</label>
                <select class="form-control" id="collection_refund_type">
                    <option name="collection_refund_type" value="1">現金</option>
                    <option name="collection_refund_type" value="0">轉帳</option>
                    <option name="collection_refund_type" value="2">其他</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">收退款日期：</label>
                <input class="form-control datepicker" id="collection_refund_create_time" name="collection_refund_create_time" value=""/>
            </div>                 
            <div class="form-group">
                <label for="message-text" class="col-form-label">備註：</label>
                <textarea class="form-control" name="collection_refund_memo" id="collection_refund_memo"></textarea>
            </div>
        <!-- </form> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="collection_refund_send">送出</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="other_fee_Modal" tabindex="-1" role="dialog" aria-labelledby="other_fee_ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="display:flex">
        <h5 class="modal-title" id="other_fee_ModalLabel">其他費用新增</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left:auto;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <form class="form-horizontal" action="/chingda/userbill/create_other_fee" method="post">          -->
            <div class="form-group">
                <label for="message-text" class="col-form-label">第一層單位</label>
                <select class="form-control" id='other_fee_grp1' name='grp1'>
                    <option value="0" selected="selected">---請選擇---</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">第二層單位</label>
                <select class="form-control" id='other_fee_grp2' name='grp2'>
                    <option value="0" selected="selected">---請選擇---</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">教授選擇:</label>
                <select class="form-control" id="other_fee_professor_id" name='other_fee_professor_id'>
                    <option value="0" selected="selected">---請選擇---</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">其他費用日期：</label>
                <input class="form-control datepicker" id="fee_create_time" name="fee_create_time" value=""/>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">其他費用金額：</label>
                <input class="form-control" id="fee_amount" name="fee_amount"/>
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">備註：</label>
                <textarea class="form-control" name="other_fee_memo" id="other_fee_memo"></textarea>
            </div>
        <!-- </form> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="other_fee_send">送出</button>
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
        $.each(usr_grp_data, function(grp1_key, grp1_value) { 
            $("#grp1").append("<option value='"+grp1_key +"'>"+grp1_value.name +"</option>");
            $("#other_fee_grp1").append("<option value="+grp1_key+">"+grp1_value.name+"</option>");
            $("#collection_refund_grp1").append("<option value="+grp1_key+">"+grp1_value.name+"</option>");          
        })
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何使用者資料"
            }
        } );

        $( ".datepicker").datepicker({ dateFormat: 'yy-mm-dd' }).val();
    } );
    $("#collection_refund_grp1").change(function(){
        var grp1 = $("#collection_refund_grp1").val();
        if(grp1 !=0){
            // 清空子分類
            $("#collection_refund_grp2").empty();
                // 填充子分類
            $("#collection_refund_grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
            // 清空子分類
            $("#collection_refund_professor_id").empty();
                // 填充子分類
            $("#collection_refund_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
            $.each(usr_grp_data[grp1]['grp2'], function(grp1_key, grp1_value) {               
                $("#collection_refund_grp2").append("<option value="+grp1_key+">"+grp1_value.grp2_name+"</option>");
                // })
            });
        }else{
            // 清空子分類
            $("#collection_refund_grp2").empty();
                // 填充子分類
            $("#collection_refund_grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
            // 清空子分類
            $("#collection_refund_professor_id").empty();
                // 填充子分類
            $("#collection_refund_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
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
    $("#grp1").change(function(){
        var grp1 = $("#grp1").val();
        if(grp1 !=0){
            // 清空子分類
            $("#grp2").empty();
                // 填充子分類
            $("#grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
            // 清空子分類
            $("#grp3").empty();
                // 填充子分類
            $("#grp3").append("<option value='0' selected='selected'>---請選擇---</option>");
            $.each(usr_grp_data[grp1]['grp2'], function(grp1_key, grp1_value) {               
                $("#grp2").append("<option value="+grp1_key+">"+grp1_value.grp2_name+"</option>");
                // })
            });
        }else{
            // 清空子分類
            $("#grp2").empty();
                // 填充子分類
            $("#grp2").append("<option value='0' selected='selected'>---請選擇---</option>");
            // 清空子分類
            $("#grp3").empty();
                // 填充子分類
            $("#grp3").append("<option value='0' selected='selected'>---請選擇---</option>");
        }       
    });
    $("#collection_refund_grp2").change(function(){
        var grp1 = $("#collection_refund_grp1").val();
        var grp2 = $("#collection_refund_grp2").val();
        if(grp1 !=0 && grp2 !=0){
            // 清空子分類
            $("#collection_refund_professor_id").empty();
                // 填充子分類
            $("#collection_refund_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
            $.each(usr_grp_data[grp1]['grp2'][grp2]['professor'], function(grp2_key, grp2_value) {
                $("#collection_refund_professor_id").append("<option value="+grp2_value.professor_id+">"+grp2_value.professor_name+"</option>");
            });
        }else{
            // 清空子分類
            $("#collection_refund_professor_id").empty();
                // 填充子分類
            $("#collection_refund_professor_id").append("<option value='0' selected='selected'>---請選擇---</option>");
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
    $("#grp2").change(function(){
        var grp1 = $("#grp1").val();
        var grp2 = $("#grp2").val();
        if(grp1 !=0 && grp2 !=0){
            // 清空子分類
            $("#grp3").empty();
                // 填充子分類
            $("#grp3").append("<option value='0' selected='selected'>---請選擇---</option>");
            $.each(usr_grp_data[grp1]['grp2'][grp2]['professor'], function(grp2_key, grp2_value) {
                $("#grp3").append("<option value="+grp2_value.professor_id+">"+grp2_value.professor_name+"</option>");
            });
        }else{
            // 清空子分類
            $("#grp3").empty();
                // 填充子分類
            $("#grp3").append("<option value='0' selected='selected'>---請選擇---</option>");
        }       
    });
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
    $('#collection_refund_send').click(function(){
        var perfessor_id = $('#collection_refund_professor_id').val();
        var collection_or_refund = $('#collection_or_refund').val();
        var collection_refund_type = $('#collection_refund_type').val();
        var collection_refund_create_time = $('#collection_refund_create_time').val();
        var collection_refund_amount = $('#collection_refund_amount').val();
        var collection_refund_memo = $('#collection_refund_memo').val();
        $.ajax({
            url: "<?=Yii::app()->createUrl('/userbill/create_collection_refund')?>",
            type: 'POST',
            data: {
                perfessor_id: perfessor_id,
                collection_or_refund: collection_or_refund,
                collection_refund_type: collection_refund_type,
                collection_refund_create_time: collection_refund_create_time,
                collection_refund_amount: collection_refund_amount,
                memo: collection_refund_memo,
            },
            error: function(xhr) {
                alert('Ajax request 發生錯誤');
            },
            success: function(response) {
                $('#collection_refund_Modal').modal('hide');
                if(response) alert('新增成功');
                else alert('新增失敗');
                location.reload();
            }
        });
    });
</script>