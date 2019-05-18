<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>員工薪資設定</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>帳號</th>
                            <th>姓名</th>
                            <th>本薪</th>
                            <th>健保</th>
                            <th>勞保</th>
                            <th>退休金提撥</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($list)):?>
                            <?php foreach($list as $data):?>
                                <?php if(empty($data['salary'])):?>
                                <tr style="background: #f0c9b4;">
                                <?php else:?>
                                <tr>
                                <?php endif;?>
                                    <td><?=$data['user_name']?></td>
                                    <td><?=$data['name']?></td>
                                    <td><?php if (!empty($data['salary'])):?><?=$data['salary']?><?php endif;?></td>
                                    <td><?php if (!empty($data['health_insurance'])):?><?=$data['health_insurance']?><?php endif;?></td>
                                    <td><?php if (!empty($data['labor_insurance'])):?><?=$data['labor_insurance']?><?php endif;?></td>
                                    <td><?php if (!empty($data['pension'])):?><?=$data['pension']?><?php endif;?></td>

                                    <td>
                                        <a href="<?= Yii::app()->createUrl('/salary/employees/edit?id='.$data['employee_id']);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
