<?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>
<div class="row">
    <div class="title-wrap col-lg-12">
        <h3 class="title-left">特殊狀況申請</h3>
    </div>
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <!-- 參數選擇 -->

        <!-- 參數選擇結束 -->

        <div class="panel panel-default">
            <!--
            <div class="panel-heading col-md-12">
                    
                    <div class='col-md-2'> 
                    <form class="form-horizontal" action="<?php echo Yii::app()->createUrl('Devreport/getexcel');?>" method="post">
                    <button type="submit" class="btn btn-default">匯出excel</button>

                    </form>                    
                    </div>
                    
                    <div class='col-md-2'>
                    <a href="<?=Yii::app()->createUrl('Devreport/printer');?>"  target="_blank">     
                    <button class="btn btn-default">列印</button>
                    </a> 
                    </div>

                <div class='col-md-2 col-sm-4 col-xs-4'>
                    
                </div>                
            </div>
            -->
            <div class="panel-body">
                <div class="row">
                
                <form action="<?=Yii::app()->createUrl('userbill/comfirmdio')?>" method="post">

                    <?php CsrfProtector::genHiddenField(); ?>
                    <input type="hidden" name="id" value="<?=$rcdata['id']?>">
                    <div class="form-group">
                    <label for="exampleInputEmail1">申請人</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="" disabled value="<?=$mem->name?>">
                    </div>
                    
                    <div class="form-group">
                    <label for="exampleInputEmail1">申請原因</label>
                    <textarea class="form-control" rows="3" placeholder="請於此表單填寫,申請調整帳單源由,如:離開時忘記刷,並清楚填寫時間點以及機台以利作業程序" name='des' disabled><?=$rcdata['des']?>
                    </textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">扣除價格</label>
                      <input type="number" id="exampleInputFile" class="form-control" name='discount' min="0" value="0">
                    </div>

                    <div class="form-group">
                      <label>
                        審核結果
                      </label>
                     <div class="radio">
                    
                    <label>
                        <input type="radio" name="status" id="optionsRadios2" value="2" checked>
                        審核失敗
                      </label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio" name="status" id="optionsRadios2" value="1">
                        審核通過
                      </label>
                    </div>

                    </div>
                    
                    <button type="submit" class="btn btn-default">審核</button>
                    </form>
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