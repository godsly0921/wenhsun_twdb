<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>

<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">每日預約明細表</h3>

    </div>
</div>
<?php foreach ($session_jsons as $jsons): ?>
    <?php if ($jsons["power_controller"] == 'reservation/get_day_list'): ?>
        <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('reservation/get_day_list');?>" method="post">
            <div class="panel-heading">

                <div class="form-group">
                    <label for="date_start" class="col-sm-2 control-label">查詢日期:</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="datepicker" name="date" placeholder="請匯出開始日期" >
                    </div>

                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-default">查詢</button>
                    </div>
                </div>

            </div>
        </form>
    <?php endif; ?>
<?php endforeach; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer"
                               role="grid">
                            <thead>
                            <tr role="row">
                                <th>儀器名稱</th>
                                <th>預約人</th>
                                <th>預約開始時間</th>
                                <th>預約結束時間</th>
                                <th>預約狀態</th>
                                <!--<th>備註</th>-->
                                <th>申請日</th>
                                <th>異動日</th>
                                <th>取消預約</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $status = [0=>'用戶預約中',1=>'預約時段正常使用',2=>'預約時間已到用戶未使用',3=>'預約取消'];
                            foreach ($model as $key => $value): ?>
                                <tr class="gradeC" role="row">
                                    <td>
                                        <?php $user_name = '';
                                        foreach($devices as $k=>$v): ?>
                                            <?php if($v->id == $value->device_id):?>
                                                    <?php $user_name = $v->name; ?>
                                                    <?= $v->name ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php
                                            if($value->builder_type == 1){
                                                foreach ($members as $k=>$v){
                                                    if($v->id == $value->builder) echo $v->name;
                                                }
                                            }else{
                                                foreach ($accounts as $k=>$v){
                                                    if($v->id == $value->builder) echo $v->account_name;
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td><?= $value->start_time  ?></td>
                                    <td><?= $value->end_time ?></td>
                                    <td> <?php foreach($status as $k=>$v): ?>
                                            <?php if($k == $value->status):?>
                                                <?= $v ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?></td>
                                    <!--<td><?/*= $value->remark */?></td>-->
                                    <td><?= $value->create_time ?></td>
                                    <td><?= $value->modify_time ?></td>
                                    <td>
                                        <?php if( $value->status == 0 ){?>
                                        <button class='btn btn-danger fa fa-times-circle-o cancelOrder' value="<?=$value->id?>"></button>
                                        <?php }?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#specialcaseTable').DataTable( {
            "scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "查詢的日期無任何預約資料"
            }
        } );
    } );
</script>

<script>
    $( function() {
        $( "#datepicker").datepicker({ dateFormat: 'yy-mm-dd' }).val();

        var table = $('#specialcaseTable').DataTable();

        $('#specialcaseTable').on('click', 'tr', function () {
            var data = table.row( this ).data();
            // alert( 'You clicked on '+data[0]+'\'s row' );

            swal({
                title: '確定要取消預約?',
                text: "您即將取消使用者："+data[1]+data[2]+"到"+data[3]+"的預約,是否確認要取消?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:  '取消',
                confirmButtonText: '確認'
            }).then((result) => {

                // 取消AJAX S
                //--------------------------------------------------------------------
                if (result.value) {
                var cancelID = $(this).find('button').attr('value');
                var csrfToken = "<?=CsrfProtector::genUserToken()?>";
                var request = $.ajax({
                    url: "<?php echo Yii::app()->createUrl('reservation/cancelReservation');?>",
                    method: "POST",
                    data: { id : cancelID , _token:csrfToken},
                    dataType: "json"
                });

                request.done(function( msg ) {

                    swal( msg );
                    location.reload();
                });

                request.fail(function( jqXHR, textStatus ) {
                    // SIG
                });
            }

            //--------------------------------------------------------------------
            // 取消AJAX E
        })
            // 確認是不是真的要取消E

        })

        } );
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
<script src="https://blackrockdigital.github.io/startbootstrap-sb-admin-2/vendor/datatables-responsive/dataTables.responsive.js"></script>