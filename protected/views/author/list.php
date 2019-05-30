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
                <form action="/author/index" method="post">
                <select name="search_category">
                    <option value="" <?php if($searchCategory === ''):?> selected <?endif;?>>請選擇</option>
                    <option value="birth_year" <?php if($searchCategory === 'birth_year'):?> selected <?endif;?>>出生年</option>
                    <option value="service" <?php if($searchCategory === "service"):?> selected <?endif;?>>服務單位</option>
                    <option value="job_title" <?php if($searchCategory === "job_title"):?> selected <?endif;?>>職稱</option>
                    <option value="address" <?php if($searchCategory === "address"):?> selected <?endif;?>>住家 郵遞區號/地址</option>
                    <option value="identity_type" <?php if($searchCategory === "identity_type"):?> selected <?endif;?>>身分類型</option>
                </select>
                <input type="text" id="search_one" name="search_one" value="<?=$searchOne?>">
                <span id="search_two_wrapper" style="display: none;">~<input type="text" id="search_two" value="<?=$searchTwo?>" name="search_two"></span>
                <input type="submit" value="查詢">
                </form>
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>筆名</th>
                            <th>姓名</th>
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
                        <?php if(!empty($list)):?>
                            <?php
                            $multiTransfer = new MultiColumnTransformer();
                            foreach($list as $data):?>
                                <tr>
                                    <td><?=$multiTransfer->toText('；', $data['pen_name']);?></td>
                                    <td><?=$data['author_name']?></td>
                                    <td><?=$data['service']?></td>
                                    <td><?=$data['job_title']?></td>
                                    <td><?=$multiTransfer->toText('；', $data['email']);?></td>
                                    <td><?=$multiTransfer->toText('；', $data['home_address']);?></td>
                                    <td><?=$multiTransfer->toText('；', $data['home_phone']);?></td>
                                    <td><?=$multiTransfer->toText('；', $data['mobile']);?></td>
                                    <td><?=$data->memo?></td>
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
                        <?php else:?>
                            <tr><td colspan="11">查無資料</td></tr>
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
            document.onmousedown = function(){
                return false;
            }
        </script>
    <?php endif; ?>
    <?php endforeach; ?>