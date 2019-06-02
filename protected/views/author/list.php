<?php
use Wenhsun\Transform\MultiColumnTransformer;
?>
<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);?>
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>作家資訊</h3>
            </div>

            <?php foreach ($session_jsons as $jsons):?>
                <?php if ($jsons["power_controller"] == 'author/new'):?>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <a href="<?= Yii::app()->createUrl('/author/new');?>">
                                    <button id="new-btn" class="btn btn-primary" type="button">新增作家</button>
                                </a>
                                <?php foreach ($session_jsons as $json):?>
                                        <?php if ($json['power_controller'] === 'author/export'):?>
                                        <button id="export_btn" class="btn btn-primary" type="button">匯出</button>
                                        <?php endif;?>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="clearfix"></div>
        
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">
                <form id="search_form" action="<?= Yii::app()->createUrl('/author/index');?>" method="post" class="form-horizontal">
                <?php CsrfProtector::genHiddenField(); ?>
                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                    <select id="search_category" name="search_category" class="form-control">
                        <option value="" <?php if($searchCategory === ''):?> selected <?php endif;?>>請選擇</option>
                        <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/pen_name'):?>
                                         <option value="pen_name" <?php if($searchCategory === 'pen_name'):?> selected <?php endif;?>>筆名</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                         <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/author_name'):?>
                                         <option value="author_name" <?php if($searchCategory === 'author_name'):?> selected <?php endif;?>>作家姓名</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                         <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/birth_year'):?>
                                         <option value="birth_year" <?php if($searchCategory === 'birth_year'):?> selected <?php endif;?>>出生年</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                         <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/service'):?>
                                         <option value="service" <?php if($searchCategory === 'service'):?> selected <?php endif;?>>服務單位</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                         <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/job_title'):?>
                                         <option value="job_title" <?php if($searchCategory === 'job_title'):?> selected <?php endif;?>>職稱</option>
                                        <?php endif;?>
                        <?php endforeach;?> <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/address'):?>
                                         <option value="address" <?php if($searchCategory === 'address'):?> selected <?php endif;?>>住家 郵遞區號/地址</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                         <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/identity_type'):?>
                                         <option value="identity_type" <?php if($searchCategory === 'identity_type'):?> selected <?php endif;?>>身分類型</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                         <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/memo'):?>
                                         <option value="memo" <?php if($searchCategory === 'memo'):?> selected <?php endif;?>>備註</option>
                                        <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                    <input type="text" id="search_one" name="search_one" value="<?=$searchOne?>" class="form-control">
                </div>

                <?php if($searchCategory === 'birth_year'):?>
                    <div id="search_two_wrapper" class="col-md-2 col-sm-2 col-xs-12 form-group"><input type="text" id="search_two" class="form-control" value="<?=$searchTwo?>" name="search_two" placeholder="至"></span></div>
                <?php else:?>
                    <div id="search_two_wrapper" class="col-md-2 col-sm-2 col-xs-12 form-group" style="display: none;"><input type="text" id="search_two" class="form-control" value="<?=$searchTwo?>" name="search_two" placeholder="至"></span></div>
                <?php endif;?>
                <input type="submit" class="btn btn-primary" value="查詢">
                </form>
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>筆名</th>
                            <th>姓名</th>
                            <th>出生日</th>
                            <th>服務單位</th>
                            <th>職稱</th>
                            <th>電子郵件</th>
                            <th>住家地址</th>
                            <th>住家電話</th>
                            <th>手機</th>
                            <th>備註</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $multiTransfer = new MultiColumnTransformer();
                            foreach($list as $data):?>
                                <tr>
                                    <td><?=$multiTransfer->toText('；', $data['pen_name']);?></td>
                                    <td><?=$data['author_name']?></td>
                                    <td><?=str_replace('-', '/', $data['birth'])?></td>
                                    <td><?=$data['service']?></td>
                                    <td><?=$data['job_title']?></td>
                                    <td><?=$multiTransfer->toText('；', $data['email']);?></td>
                                    <td><?=$multiTransfer->toText('；', $data['home_address']);?></td>
                                    <td><?=$multiTransfer->toText('；', $data['home_phone']);?></td>
                                    <td><?=$multiTransfer->toText('；', $data['mobile']);?></td>
                                    <td><?=$data['memo']?></td>
                                    <td>
                                        <a href="<?= Yii::app()->createUrl('/author/view?id='.$data['id']);?>"><i class="fa fa-newspaper-o" style="font-size:18px"></i></a>
                                    <?php foreach ($session_jsons as $jsons):?>
                                        <?php if ($jsons["power_controller"] == 'author/edit'):?>
                                        <a href="<?= Yii::app()->createUrl('/author/edit?id='.$data['id']);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

            $('#datatable').DataTable({
                "lengthChange": false,
                "paging": true,
                "responsive": true,
                "info": false,
                'iDisplayLength': 20,
                "oLanguage": {
                    "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"},
                    "sEmptyTable": "查無資料, 快去新增資料吧"
                }
            });

            $("#search_category").on('change', function(){
                if ($(this).val() === "birth_year") {
                    $("#search_two_wrapper").show();
                } else {
                    $("#search_two_wrapper").hide();
                }
            });

            $("#export_btn").on("click", function(){
                if ($(".dataTables_empty").length === 1) {
                    alert("無資料匯出");
                    return false;
                }
                $("#export_form").remove();
                let exportForm = $("#search_form").clone().appendTo($("body")).hide();
                exportForm.prop("action", "<?= Yii::app()->createUrl('/author/export');?>");
                exportForm.prop("id", "export_form");
                exportForm.submit();
            });
        });
    </script>
    <?php foreach ($session_jsons as $jsons):?>
    <?php if ($jsons["power_controller"] === 'author/copy_prohibited'):?>
        <script type="text/javascript">
            document.oncontextmenu = function(){
                return false;
            }
            document.onselectstart = function(){
                return false;
            }
        </script>
    <?php endif; ?>
    <?php endforeach; ?>