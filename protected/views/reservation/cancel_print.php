<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳號明細表</h3>
    </div>

    <DIV class="col-lg-12" id='btnbox'>
    <FORM>
    <INPUT NAME="print" TYPE="button" VALUE="列印此頁" class='btn '
    ONCLICK="varitext()">
    </FORM>
    </DIV>

</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">


        <div class="panel panel-default">

            <div class="panel-body">
                <style type="text/css">
                #btnbox{
                    margin-bottom: 40px;
                }
                #printtable{
                }
                #printtable >thead th{
                    border:1px solid black;
                    text-align: center;                    
                }
                #printtable td{
                    border:1px solid black;
                    text-align: center;
                }
                </style>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="printtable" width="100%"
                               class="">
                            <thead>
                            <tr role="row">
                                <th>儀器名稱</th>
                                <th>預約人</th>
                                <th>預約開始時間</th>
                                <th>預約結束時間</th>
                                <th>是否正常使用</th>
                                <th>取消人員</th>
                                <th>取消原因</th>
                                <!--<th>備註</th>-->
                                <th>申請日</th>
                                <th>異動日</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($model!=0):?>
                                <?php foreach ($model as $key => $value): ?>
                                    <tr class="gradeC" role="row">
                                        <td>
                                            <?php foreach ($devices as $k => $v): ?>
                                                <?php if ($v->id == $value->device_id): ?>
                                                    <?= $v->name ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php foreach ($members as $k => $v): ?>
                                                <?php if ($v->id == $value->builder): ?>
                                                    <?= $v->name ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= $value->start_time ?></td>
                                        <td><?= $value->end_time ?></td>
                                        <td><?php foreach ($categorys as $k => $v): ?>
                                                <?php if ($k == $value->status): ?>
                                                    <?= $v ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?></td>
                                        <td>
                                            <?php foreach ($accounts as $k => $v): ?>
                                                <?php if ($v->id == $value->canceler): ?>
                                                    <?= $v->account_name ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= $value->remark ?></td>
                                        <!--<td><? /*= $value->remark */ ?></td>-->
                                        <td><?= $value->create_time ?></td>
                                        <td><?= $value->modify_time ?></td>

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
<script type="text/javascript">
    
    $( function() {
        $( "#datepicker_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#datepicker_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

</script>

<SCRIPT LANGUAGE="JavaScript">
function varitext(text){
//text=document
//print($(".panel-body"))

    var content = $(".panel-body").html();
    var mywindow = window.open('', 'Print', 'height=600,width=800');

    mywindow.document.write('<html><head><title>Print</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write(content);
    mywindow.document.write('</body></html>');

    mywindow.document.close();
    mywindow.focus()
    mywindow.print();
    mywindow.close();
    return true;
}
</script>
