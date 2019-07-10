<?php echo (isset(Yii::app()->session['page_msg']) && Yii::app()->session['page_msg'] != '') ? Yii::app()->session['page_msg'] : ''; ?>
<?php unset(Yii::app()->session['page_msg']); ?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->createUrl('/assets/site/css/newslist.css');?>">
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#news">公布欄</a></li>
    <?php if(Yii::app()->session['personal'] == false){?>
      <!--  <li><a data-toggle="tab" href="#device">儀器即時監控</a></li>-->
       <!-- <li><a data-toggle="tab" href="#door">門禁即時監控</a></li>-->
    <?php }?>
</ul>

<div class="tab-content">
    <div id="news" class="tab-pane fade in active">
        <div class="row">
            <div class="title-wrap col-lg-12">
                <h3 class="title-left">公布欄</h3>
            </div>
        </div><?php $session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']); ?>

        <div class="panel panel-default">
            <div class="panel-body panel-body-background">
                <?php
                $i=1;
                foreach ($data as $key => $value) {
                    $explode_new_image = explode(".", $value['new_image']);
                    $fileType = end($explode_new_image);
                   /* if($fileType == 'pdf'){
                        $fileDownload = 1;
                    }else{
                        $fileDownload = 0;
                    }*/
                ?>
                <div class='newsbox col-md-12 col-sm-12 col-xs-12 np_' >
                    <div class='newsleft col-md-1 col-md-offset-1 col-sm-1 col-xs-1 np_'>
                        <div class='lefttop col-md-12 col-sm-12 col-xs-12 np_'>                       
                        </div>
                        <?php if( $i == $total){?>
                        <div class='leftbottom lastbot col-md-12 col-sm-12 col-xs-12 np_'>
                        </div>
                        <?php }else{?>
                        <div class='leftbottom col-md-12 col-sm-12 col-xs-12 np_'>
                        </div>
                        <?php }?>
                    </div>
                    <div class='newsright col-md-10 col-sm-11 col-xs-11'>
                        <div class='newsmain col-md-12 col-sm-12 col-xs-12'>         
                            <h4><?=$value->new_title?></h4>                       
                            <hr>                   
                            <p>
                                <?php
                                    echo nl2br($value->new_content);
                                ?>

                                <span class='about'>
                                <?php
                                $account_name = '';
                                foreach ($account as $v):?>
                                    <?php if($value->builder  == $v->id):
                                        $account_name = $v->account_name ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?='公告時間:'.$value->new_createtime.' -'.'   '.'建檔人：'.$account_name?>
                            </span>
                            </p>                       

                            <div style="float:right">
                                <?php if($value['new_image']!=""):?>
                                <a href="<?php echo Yii::app()->createUrl('news/download')."?id={$value['id']}";?>">
                                <?php
                                    $file_name = explode('/',$value['new_image']);
                                ?>
                                <span class='btn downloadBtn'>附件下載 <?=$value['image_name'] !=''?$value['image_name']:$file_name[count($file_name)-1]?></span>                      
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $i++;
                }
                ?>
            </div>
        </div>
    </div>
    <?php if(Yii::app()->session['personal'] == false){?>
    <div id="device" class="tab-pane fade">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                        <?php
                        foreach ($deviceMonitor as $key => $data) {
                        ?>
                        <div class='col-sm-6 col-md-4 panelbox'>
                          <div class="panel panel-primary np_">
                            <div class="panel-heading"><?=$data['name']?></div>
                            <div class="panel-body devbdy">
                                <p class="text-muted">位置:<?=$data['lname']?></p>
                                <p class="text-muted">站號:<?=$data['station']?></p>
                                <p class="text-muted">型號:<?=$data['codenum']?></p>
                                <p class="text-muted">機台IP:<?=$data['ip']?></p>
                                <p class="text-muted">目前狀態:<?=($data['type']==0)?'關電':'送電' ?></p>
                                <p class="text-muted">最後用戶名稱:<?=($data['use_name']!=NULL)?$data['use_name']:'無'?></p>
                                <p class="text-muted">最後使用時間:<?=($data['last_time']!=NULL)?$data['last_time']:'無'?></p>
                            </div>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="door" class="tab-pane fade">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                        <?php
                        foreach ($doorMonitor as $key => $data) {
                        ?>
                        <div class='col-sm-6 col-md-6 panelbox'>
                          <div class="panel panel-primary np_">
                            <div class="panel-heading"><?php echo $data['name']; ?></div>
                            <div class="panel-body devbdy">
                                <p class="text-muted">位置:<?=$data['lname']?></p>
                                <p class="text-muted">站號:<?=$data['station']?></p>
                                <p class="text-muted">型號:</p>
                                <p class="text-muted">IP:<?=$data['ip']?></p>
                                <p class="text-muted">時間:<?=$data['usedate']?></p>
                                <p class="text-muted">刷卡人:<?=$data['username']?></p>
                                <p class="text-muted">目前狀態:關閉</p>
                            </div>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }?>
</div>



<script type="text/javascript">
    $(function(){
        $(".saw").click(function(){

            tmp = $(this);
            var request = $.ajax({
            url: "<?php echo Yii::app()->createUrl('/news/newsview');?>",
            method: "POST",
            data: { news :  $(this).attr('newsid'),
                    mid  :  $(this).attr('memid') },
            dataType: "JSON"
            });
 
            request.done(function( msg ) {
                if(msg === true){
                  tmp.addClass('heavsaw');
                  tmp.text('已讀取');
                }
            });
 
            request.fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            });

        })
    })
</script>