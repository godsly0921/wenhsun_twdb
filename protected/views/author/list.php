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
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>筆名</th>
                            <th>姓名</th>
                            <th>電子郵件</th>
                            <th>住家電話</th>
                            <th>手機</th>
                            <th>備註</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($list)):?>
                            <?php
                            $multiTransfer = new MultiColumnTransformer();
                            foreach($list as $data):?>
                                <tr>
                                    <td><?=$multiTransfer->toText('；', $data->pen_name);?></td>
                                    <td><?=$data->author_name?></td>
                                    <td><?=$multiTransfer->toText('；', $data->email);?></td>
                                    <td><?=$multiTransfer->toText('；', $data->home_phone);?></td>
                                    <td><?=$multiTransfer->toText('；', $data->mobile);?></td>
                                    <td><?=$data->memo?></td>
                                    <td>
                                        <a href="<?= Yii::app()->createUrl('/author/edit?id='.$data->id);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr><td colspan="6">查無資料, 快去<a href="<?= Yii::app()->createUrl('/author/new');?>">新增資料</a>吧</td></tr>
                        <?php endif; ?>
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
                    "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁","sNext": "下一頁","sLast": "最後一頁"}
                }
            });

            $('body').bind('cut copy paste', function (e) {
                e.preventDefault();
            });
        });
    </script>