<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
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
                        <label class="col-sm-2 control-label">起始日期:</label>
                        <input type="text" class="form-control datepicker" name="date_start" placeholder="請選擇起始日期" >
                        <label class="col-sm-2 control-label">結束日期:</label>
                        <input type="text" class="form-control datepicker" name="date_end" placeholder="請選擇結束日期" >
                    </div>


                    <div class="form-group">
                        <label for="sort" class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                        <button type="submit" class="btn btn-default">查詢</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- 參數選擇結束 -->

        <div class="panel panel-default">

            <div class="panel-heading col-md-12">

                <div class='col-md-2'>
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('userbill/getexcelList');?>" method="post">

                        <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>
                </div>

                <div class='col-md-2'>
                    <a href="<?=Yii::app()->createUrl('userbill/printerList');?>"  target="_blank">
                        <button class="btn btn-default">列印</button>
                    </a>
                </div>

                <div class='col-md-2'>
                    <form action="<?=Yii::app()->createUrl('userbill/allMemberBill');?>" method="post" target="_blank">
                        <input type="hidden" name="date_start" value="<?=$date_start?>">
                        <input type="hidden" name="date_end" value="<?=$date_end?>">
                        <input type="submit" class="btn btn-default" value="全部成員帳單"/>
                    </form>
                </div>                

                <div class='col-md-2 col-sm-4 col-xs-4'>

                </div>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr role="row">
                                <th>教授姓名</th>
                                <th>學員姓名</th>
                                <th>登入帳號</th>
                                <th>聯絡電話</th>
                                <th>E-mail</th>
                                <th>帳單狀態</th>
                                <th>查看帳單</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($datas!=0):?>
                            <?php foreach ($datas as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?php foreach ($professor as $k => $v): ?>
                                            <?php if($v->id == $value['professor']): ?>
                                                <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach ?></td>
                                    <td>
                                        <?=$value['name']?>
                                    </td>
                                    <td>
                                        <?=$value['account']?>
                                    </td>
                                    <td>
                                        <?=$value['phone1']?>
                                    </td>
                                    <td><?=$value['email1']?></td>
                                    <td>
                                    <?php
                                    if($value['payoff']== true){
                                    ?>
                                    <span style='color:green;'>無帳款</span> 
                                    <?php
                                    }else{
                                    ?>
                                    <span style='color:red;'>未繳清</span> 
                                    <?php
                                    }
                                    ?>
                                    </td>
                                    <td>
                                        <form action="<?=Yii::app()->createUrl('userbill/billview').'/'.$value['id'];?>" method="post" target="_blank">
                                            <input type="hidden" name="date_start" value="<?=$date_start?>">
                                            <input type="hidden" name="date_end" value="<?=$date_end?>">
                                            <button type="submit" class="btn btn-link">
                                                [<span class="fa fa-search " aria-hidden="true"></span>查看]
                                            </button>
                                        </form>
                                        <br>
                                        <form action="<?=Yii::app()->createUrl('userbill/billpay').'/'.$value['id'];?>" method="post" target="_blank">
                                            <input type="hidden" name="date_start" value="<?=$date_start?>">
                                            <input type="hidden" name="date_end" value="<?=$date_end?>">
                                            <button type="submit" class="btn btn-link">
                                                [<span class="fa fa-dollar" aria-hidden="true"></span>繳費]
                                            </button>
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
<script
    src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/datatables-responsive/dataTables.responsive.js"></script>
<script>
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
    } );

    
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
                    console.log(val);
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
                    console.log(val);
                    $("#grp3").append("<option value="+val['id']+">"+val['name']+"</option>");
                });


            });

            request.fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            });

        }

    });
</script>