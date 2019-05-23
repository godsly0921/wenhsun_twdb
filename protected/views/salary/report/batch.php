<div role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>薪資報表 (<?=$batch_id?>)</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>員工帳號</th>
                            <th>員工姓名</th>
                            <th>應稅薪資合計</th>
                            <th>薪資合計</th>
                            <th>應扣合計</th>
                            <th>實領薪資</th>
                            <th>狀態</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($list)):?>
                            <?php foreach($list as $data):?>
                                <?php if($data['status'] === 'YET'):?>
                                <tr style="background: #f0c9b4;">
                                <?php else:?>
                                <tr>
                                <?php endif;?>
                                    <td><?php if (!empty($data['employee_login_id'])):?><?=$data['employee_login_id']?><?php endif;?></td>
                                    <td><?php if (!empty($data['employee_name'])):?><?=$data['employee_name']?><?php endif;?></td>
                                    <td><?php if (!empty($data['taxable_salary_total'])):?><?=number_format($data['taxable_salary_total'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['salary_total'])):?><?=number_format($data['salary_total'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['deduction_total'])):?><?=number_format($data['deduction_total'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['real_salary'])):?><?=number_format($data['real_salary'])?><?php endif;?></td>
                                    <td><?php if (!empty($data['status'])):?><?=$data['status']?><?php endif;?></td>
                                    <td>
                                        <a href="<?= Yii::app()->createUrl('/salary/report/employee?batchId=' . $batch_id . '&employeeId=' . $data['employee_id']);?>"><i class="fa fa-edit" style="font-size:18px"></i></a>
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
