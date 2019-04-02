<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">教授已結帳資料</h3>
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
                <form action="<?php echo Yii::app() -> createUrl('userbill/bill_record'); ?>"  method="POST" >

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
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- 參數選擇結束 -->
        <!-- 本期收退款資料 start -->
        <div class="panel panel-default">
            <div class="panel-body">
            <div class="row">
                <div class="title-wrap col-lg-12">
                    <h3 class="title-left">本期收退款資料</h3>
                </div>
            </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>筆數</th>
                                <th>教授姓名</th>
                                <th>結帳日期</th>
                                <th>上期餘額</th>
                                <!-- <th>收款 / 退款</th> -->
                                <th>本期收退款金額</th>                                
                                <th>本期其他金額</th>
                                <th>本期機台費</th>
                                <th>本期門禁費</th>
                                <th>本期餘額</th>
                                <!-- <th>備註</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($result)!=0):?>
                            <?php foreach ($result as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?=$key+1?></td>
                                    <td><?=$value['grp1_name']?><br><?=$value['grp2_name']?><br><?=$value['professor_name']?></td>
                                    <td>
                                        <form class="form-horizontal" action="/chingda/userbill/allstudenthistoryexcel" method="post">
                                            <input type="hidden" name="bill_record_id" value="<?=$value['bill_record_id']?>"/>
                                            <input type="hidden" name="professor_id" value="<?=$value['professor_id']?>"/>
                                            <label><?=$value['checkout_time']?></label><br>
                                            <button type="submit" class="btn btn-default">匯出excel</button>
                                        </form>
                                    </td>
                                    <td><?=$value['opening_balance']?></td>                        
                                    <!-- <td><?#=$value['bill_type']?></td> -->
                                    <td><?=$value['pay_amount']?></td>
                                    <td><?=$value['other_fee']?></td>
                                    <td><?=$value['device_fee']?></td>
                                    <td><?=$value['door_fee']?></td>
                                    <td><?=$value['ending_balance']?></td>
                                    <!-- <td><?#=$value['memo']?></td> -->
                                </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <!-- 本期收退款資料 end -->
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
        })
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何使用者資料"
            }
        } );
        $('#otherfeeTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何使用者資料"
            }
        } );
        $( ".datepicker").datepicker({ dateFormat: 'yy-mm-dd' }).val();
    } );

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
</script>