<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">使用者帳號明細表查詢</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->
        <div class="panel panel-default">
            <div class="panel-heading">查詢條件設定</div>
            <div class="panel-body">
                <form action="<?php echo Yii::app() -> createUrl('member/get_detail_list'); ?>"  method="POST" >
                    <input type="hidden" name="filter" value="1">
                    <div class="form-group col-md-12">
                        <label for="date_start" class="col-sm-2 control-label"> 關鍵字:</label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control" name="keyword" placeholder="請輸入關鍵字" >
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="col-sm-2 control-label"> 關鍵字欄位:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="keyword_field">
                                    <option value="1" selected="selected">使用者姓名</option>
                                    <option value="2">帳號(身分證字號)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="sort" class="col-sm-2 control-label"> 使用者身份: </label>
                        <div class="col-sm-6">
                            <select class="form-control" name="user_group" id="sort">
                            <option value="0" selected="selected">全部</option>
                                <?php foreach($groups as $group): ?>
                                        <option value="<?= $group->group_number ?>">
                                            <?= $group->group_name ?>
                                        </option>
                                <?php endforeach; ?>
                            </select>
                      </div>
                    </div>

                    <div class="form-group col-md-12 ">

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
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('member/getexcel');?>" method="post">
                        <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>
                </div>

                <div class='col-md-2'>
                    <a href="<?=Yii::app()->createUrl('member/printer');?>"  target="_blank">
                        <button class="btn btn-default">列印</button>
                    </a>
                </div>

            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="specialcaseTable" width="100%"
                               class="table table-striped table-bordered table-hover dataTable no-footer">
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
    } );
    $('#myTable').DataTable({
        responsive: true,
        columnDefs: [
            {responsivePriority: 1, targets: 0},
            {responsivePriority: 2, targets: -1}
        ]
    });
    $(document).ready(function () {
        $('#calculationfeeTable').DataTable({
            "scrollX": true,
            "lengthChange": true,
            "paging": true,
            "info": true,
            "order": [[4, "desc"], [0, "asc"]],
            "columnDefs": [{"targets": 5, "orderable": false}],
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
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
</script>
<script type="text/javascript">
    
    $( function() {
        $( "#datepicker_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
        $( "#datepicker_end" ).datepicker({ dateFormat: 'yy-mm-dd' });
    });

</script>