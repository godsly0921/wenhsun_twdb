<div class="right_col">
    <div class="row">
        <div class="title-wrap col-lg-12">
            <h3 class="title-left">分類</h3>
        </div>
    </div>

    <?php if(isset(Yii::app()->session['error_msg']) && Yii::app()->session['error_msg'] !== ''): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (Yii::app()->session['error_msg'] as $error): ?>
                    <li><?= $error[0] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(isset(Yii::app()->session['success_msg']) && Yii::app()->session['success_msg'] !== ''): ?>
    <div class="alert alert-success">
    <strong>新增成功!</strong><?=Yii::app()->session['success_msg'];?>
    </div>
    <?php endif; ?>

    <?php
    unset( Yii::app()->session['error_msg'] );
    unset( Yii::app()->session['success_msg'] );
    ?>
    <form name="group_form" class="form-horizontal" action="<?php echo Yii::app()->createUrl('category/new');?>" method="post">

        <?php CsrfProtector::genHiddenField(); ?>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label for="adv_id" class="col-sm-2 control-label">分類名稱:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="name" name="name" placeholder="請輸入分類名稱" value="" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">歸屬大類:</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="parents" required>
                            <option value="0">無(此設定為第一層分類)</option>
                            
                            <?php if($categorys){
                            foreach($categorys as $category): ?>
                            
                            <option value="<?=$category->category_id;?>">
                                <?= $category->name;  ?>
                            </option>                      
                            <?php endforeach; ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>     
                <div class="form-group">
                    <label for="adv_id" class="col-sm-2 control-label">選單排序:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="sort" name="sort" placeholder="請輸入選單排序" value="" required>
                    </div>
                </div>     
                <div class="form-group">
                    <label class="col-sm-2 control-label">啟用:</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="status" required>                      
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </div>
                </div>   
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">新增</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>