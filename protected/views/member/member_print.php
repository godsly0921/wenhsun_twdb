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
                                <th>使用者姓名</th>
                                <th>帳號</th>
                                <th>使用者身分</th>
                                <th>性別</th>
                                <th>第一層/第二層單位</th>
                                <th>指導教授</th>
                                <th>聯絡電話</th>
                                <th>作廢日期</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($datas!=0):?>
                            <?php foreach ($datas as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td><?=$value->name?></td>
                                    <td><?=$value->account?></td>
                                    <td>
                                        <?php foreach ($groups as $group):?>
                                            <?php if($value->user_group == $group->group_number):?>
                                                <?= $group->group_name ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php if ($value->sex == 0): ?>
                                            <?= '女生' ?>
                                        <?php elseif ($value->sex == 1): ?>
                                            <?= '男生' ?>
                                        <?php elseif ($value->sex == 2): ?>
                                            <?= '未設定' ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                        <?php foreach ($grp_data as $grp_key => $grp_val): ?>
                                            <?php if($grp_val->id == $value->grp_lv1): ?>
                                                <?= $grp_val->name ?>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                        <?='/'?>
                                        <?php foreach ($grp_data2 as $grp_key => $grp_val): ?>
                                            <?php if($grp_val->id == $value->grp_lv2): ?>
                                                <?= $grp_val->name ?>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td><?php foreach ($professor as $k => $v): ?>
                                            <?php if($v->id == $value->professor): ?>
                                                <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach ?></td>
                                    <td><?=$value->phone1?></td>
                                    <td><?=$value->invalidation_date?></td>

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
